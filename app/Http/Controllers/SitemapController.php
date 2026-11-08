<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return response()->view('sitemap', [
            'posts' => Post::where('is_published', true)->whereNotNull('slug')->where('slug', '!=', '')->get(['slug', 'updated_at']),
            'portfolios' => Portfolio::where('is_active', true)->whereNotNull('slug')->where('slug', '!=', '')->get(['slug', 'updated_at']),
        ])->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
