<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MarketingController extends Controller
{
    public function index(): View
    {
        return view('admin.marketing.index', ['drafts' => config('marketing.article_drafts')]);
    }

    public function store(string $draft): RedirectResponse
    {
        $article = config('marketing.article_drafts')[$draft] ?? null;
        abort_if($article === null, 404);

        $post = Post::firstOrCreate(['slug' => $draft], [
            'title' => $article['title'],
            'content' => view('admin.marketing.article', ['article' => $article])->render(),
            'is_published' => false,
            'published_at' => null,
        ]);

        return redirect()->route('admin.posts.edit', $post)
            ->with('success', 'Draft siap ditinjau. Tambahkan pengalaman dan gambar asli sebelum menerbitkan.');
    }
}
