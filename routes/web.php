<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Admin\MarketingController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Gtk2026Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PembelajaranController;
use App\Http\Controllers\PostReactionController;
use App\Http\Controllers\SitemapController;
use App\Models\Activity;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Slider;
use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

Route::get('/app.webmanifest', function (): JsonResponse {
    $manifest = json_decode(file_get_contents(public_path('manifest.webmanifest')), true, flags: JSON_THROW_ON_ERROR);

    return response()->json($manifest, 200, [
        'Content-Type' => 'application/manifest+json',
        'Cache-Control' => 'no-cache',
    ]);
})->name('pwa.manifest');

Route::get('/', function (Request $request): View {
    $validated = $request->validate([
        'search' => 'nullable|string|max:255',
    ], [
        'search.string' => 'Kata pencarian harus berupa teks.',
        'search.max' => 'Kata pencarian maksimal 255 karakter.',
    ]);
    $search = $validated['search'] ?? null;

    $profile = Profile::first();
    $skills = Skill::where('is_active', true)->orderBy('sort_order')->get();
    $experiences = Experience::orderBy('sort_order')->orderBy('start_date', 'desc')->get();
    $educations = Education::orderBy('sort_order')->orderBy('start_year', 'desc')->get();
    $socialLinks = SocialLink::where('is_active', true)->orderBy('sort_order')->get();

    $portfoliosQuery = Portfolio::where('is_active', true)->orderBy('sort_order')->orderBy('created_at', 'desc');
    $postsQuery = Post::with('category')->where('is_published', true)->orderBy('published_at', 'desc');

    if ($search !== null && $search !== '') {
        $portfoliosQuery->where(function (Builder $q) use ($search): void {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });

        $postsQuery->where(function (Builder $q) use ($search): void {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        });
    }

    $portfolios = $portfoliosQuery->get();
    $activities = Activity::where('is_active', true)->orderBy('date', 'desc')->take(3)->get();

    $blogPosts = $postsQuery->orderByDesc('id')
        ->paginate(6, ['*'], 'blog_page')
        ->withQueryString()
        ->fragment('blog');

    $groupedPosts = $blogPosts->getCollection()->groupBy(function (Post $post): string {
        return $post->category ? $post->category->name : 'Uncategorized';
    })->map(function (Collection $categoryPosts): Collection {
        return $categoryPosts->groupBy(function (Post $post): string {
            return ($post->published_at ?? $post->created_at)->format('F Y');
        });
    });

    $settings = Setting::pluck('value', 'key')->toArray();
    $sliders = Slider::where('is_active', true)->orderBy('sort_order')->orderBy('created_at', 'desc')->get();

    return view('welcome', compact('profile', 'skills', 'experiences', 'educations', 'socialLinks', 'portfolios', 'activities', 'blogPosts', 'groupedPosts', 'settings', 'sliders', 'search'));
})->name('site.home');

Route::get('/profil', function (): View {
    $profile = Profile::first();
    $skills = Skill::where('is_active', true)->orderBy('sort_order')->get();
    $experiences = Experience::orderBy('sort_order')->orderBy('start_date', 'desc')->get();
    $educations = Education::orderBy('sort_order')->orderBy('start_year', 'desc')->get();
    $socialLinks = SocialLink::where('is_active', true)->orderBy('sort_order')->get();
    $portfolios = Portfolio::where('is_active', true)->orderBy('sort_order')->orderBy('created_at', 'desc')->get();
    $settings = Setting::pluck('value', 'key')->toArray();

    return view('profile', compact('profile', 'skills', 'experiences', 'educations', 'socialLinks', 'portfolios', 'settings'));
})->name('profil');

Route::get('/tentang', fn () => redirect()->route('profil'));

Route::get('/portfolio/{slug}', function (string $slug): View {
    $portfolio = Portfolio::where('slug', $slug)->where('is_active', true)->firstOrFail();
    $profile = Profile::first();
    $settings = Setting::pluck('value', 'key')->toArray();
    $socialLinks = SocialLink::where('is_active', true)->orderBy('sort_order')->get();

    return view('portfolio.show', compact('portfolio', 'profile', 'settings', 'socialLinks'));
})->name('portfolio.show');

Route::get('/blog/{slug}', function (string $slug): View {
    $post = Post::with(['category', 'tags'])->where('slug', $slug)->where('is_published', true)->firstOrFail();

    // Increment view count
    $post->increment('views');

    $profile = Profile::first();
    $settings = Setting::pluck('value', 'key')->toArray();
    $socialLinks = SocialLink::where('is_active', true)->orderBy('sort_order')->get();

    // Get related posts by tags or category
    $relatedPosts = collect();
    if ($post->tags->count() > 0) {
        $tagIds = $post->tags->pluck('id')->toArray();
        $relatedPosts = Post::whereHas('tags', function ($query) use ($tagIds) {
            $query->whereIn('tags.id', $tagIds);
        })->where('id', '!=', $post->id)
            ->where('is_published', true)
            ->take(3)
            ->get();
    }

    if ($relatedPosts->isEmpty() && $post->category_id) {
        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();
    }

    if ($relatedPosts->isEmpty()) {
        $relatedPosts = Post::where('id', '!=', $post->id)
            ->where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();
    }

    $postDate = $post->published_at ?? $post->created_at;

    $prevPost = Post::where('is_published', true)
        ->where('id', '!=', $post->id)
        ->where(function ($query) use ($postDate, $post) {
            $query->where('published_at', '<', $postDate)
                ->orWhere(function ($q) use ($postDate, $post) {
                    $q->where('published_at', '=', $postDate)
                        ->where('id', '<', $post->id);
                });
        })
        ->orderBy('published_at', 'desc')
        ->orderBy('id', 'desc')
        ->first();

    $nextPost = Post::where('is_published', true)
        ->where('id', '!=', $post->id)
        ->where(function ($query) use ($postDate, $post) {
            $query->where('published_at', '>', $postDate)
                ->orWhere(function ($q) use ($postDate, $post) {
                    $q->where('published_at', '=', $postDate)
                        ->where('id', '>', $post->id);
                });
        })
        ->orderBy('published_at', 'asc')
        ->orderBy('id', 'asc')
        ->first();

    return view('blog.show', compact('post', 'profile', 'settings', 'socialLinks', 'relatedPosts', 'prevPost', 'nextPost'));
})->name('blog.show');

Route::post('/blog/{slug}/react', [PostReactionController::class, 'react'])->name('blog.react');

Route::get('/kulapos', [LandingPageController::class, 'kulapos'])->name('kulapos');
Route::get('/gtk2026', [Gtk2026Controller::class, 'index'])->name('gtk2026');
Route::get('/gtk2026/presenter', [Gtk2026Controller::class, 'presenter'])->middleware('auth')->name('gtk2026.presenter');
Route::get('/gtk2026/worker.js', [Gtk2026Controller::class, 'worker'])->name('gtk2026.worker');
Route::get('/gtk2026/media/{evidence}', [Gtk2026Controller::class, 'media'])->where('evidence', '[a-z]+')->name('gtk2026.media');
Route::get('/gtk2026/evidence', [Gtk2026Controller::class, 'evidence'])->middleware('auth')->name('gtk2026.evidence');
Route::post('/gtk2026/evidence/{evidence}/media', [Gtk2026Controller::class, 'uploadEvidence'])->where('evidence', '[a-z]+')->middleware('auth')->name('gtk2026.evidence.upload');
Route::delete('/gtk2026/evidence/{evidence}/media', [Gtk2026Controller::class, 'deleteEvidence'])->where('evidence', '[a-z]+')->middleware('auth')->name('gtk2026.evidence.delete');
Route::get('/gtk2026/evidence/{evidence}/media', [Gtk2026Controller::class, 'privateMedia'])->where('evidence', '[a-z]+')->middleware('auth')->name('gtk2026.evidence.media');
Route::get('/jasa-aplikasi', [LandingPageController::class, 'services'])->name('services');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// Microsite Pembelajaran Informatika Kelas XII (Belajar Membaca Kode Python)
Route::prefix('pembelajaran')->group(function () {
    Route::get('/', [PembelajaranController::class, 'index'])->name('pembelajaran.index');
    Route::get('/pertemuan/{id}', [PembelajaranController::class, 'pertemuan'])
        ->whereNumber('id')
        ->name('pembelajaran.pertemuan');
    Route::get('/asesmen', [PembelajaranController::class, 'asesmen'])->name('pembelajaran.asesmen');
    Route::get('/hasil', [PembelajaranController::class, 'hasil'])->name('pembelajaran.hasil');
});

Auth::routes([
    'register' => false, // Disable registration
    'reset' => false, // Disable password reset
    'verify' => false, // Disable email verification
]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Admin Routes
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/content', [AdminController::class, 'content'])->name('admin.content');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

    // Skills
    Route::resource('skills', SkillController::class, ['as' => 'admin']);

    // Experiences
    Route::resource('experiences', ExperienceController::class, ['as' => 'admin']);

    // Educations
    Route::resource('educations', EducationController::class, ['as' => 'admin']);

    // Social Links
    Route::resource('social-links', SocialLinkController::class, ['parameters' => ['social-links' => 'socialLink'], 'as' => 'admin']);

    // Phase 3 Modules
    Route::resource('portfolios', PortfolioController::class, ['as' => 'admin']);
    Route::resource('activities', ActivityController::class, ['as' => 'admin']);
    Route::resource('categories', CategoryController::class, ['as' => 'admin']);
    Route::resource('tags', TagController::class, ['as' => 'admin']);
    Route::resource('posts', PostController::class, ['as' => 'admin']);
    Route::resource('sliders', SliderController::class, ['as' => 'admin']);
    Route::post('/upload-image', [ImageUploadController::class, 'upload'])->name('admin.upload.image');

    // Settings
    Route::get('/marketing', [MarketingController::class, 'index'])->name('admin.marketing.index');
    Route::post('/marketing/drafts/{draft}', [MarketingController::class, 'store'])->name('admin.marketing.drafts.store');
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});
