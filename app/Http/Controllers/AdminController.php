<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Skill;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the admin dashboard.
     */
    public function dashboard(): View
    {
        $portfolioCount = Portfolio::count();
        $postCount = Post::count();
        $activityCount = Activity::count();
        $skillCount = Skill::count();

        $latestPosts = Post::latest()->take(5)->get();
        $latestPortfolios = Portfolio::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'portfolioCount',
            'postCount',
            'activityCount',
            'skillCount',
            'latestPosts',
            'latestPortfolios'
        ));
    }

    public function content(): View
    {
        return view('admin.content');
    }
}
