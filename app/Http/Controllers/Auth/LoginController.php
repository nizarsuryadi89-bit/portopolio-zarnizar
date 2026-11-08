<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Setting;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\View\View;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected function redirectTo(): string
    {
        return route('admin.dashboard');
    }

    public function showLoginForm(): View
    {
        $profile = Profile::first();
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('auth.login', compact('profile', 'settings'));
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
