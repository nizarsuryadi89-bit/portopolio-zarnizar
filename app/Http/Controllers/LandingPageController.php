<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function kulapos(): View
    {
        return $this->page('kulapos', ['kasir', 'kulapos', 'offline', 'penjualan']);
    }

    public function services(): View
    {
        return $this->page('services', ['sistem', 'stok', 'erp', 'website', 'aplikasi']);
    }

    /**
     * @param  list<string>  $keywords
     */
    private function page(string $page, array $keywords): View
    {
        $relatedPosts = Post::where('is_published', true)
            ->where(function (Builder $query) use ($keywords): void {
                foreach ($keywords as $keyword) {
                    $query->orWhere('title', 'like', '%'.$keyword.'%');
                }
            })
            ->orderByDesc('published_at')->orderByDesc('id')->take(3)->get();

        return view('marketing.'.$page, [
            'settings' => Setting::pluck('value', 'key')->toArray(),
            'relatedPosts' => $relatedPosts,
            'portfolios' => $page === 'services'
                ? Portfolio::where('is_active', true)->whereNotNull('slug')->where('slug', '!=', '')->orderBy('sort_order')->latest()->take(6)->get()
                : collect(),
        ]);
    }
}
