<?php

use App\Models\User;

test('login renders without profile settings and provides a usable login form', function (): void {
    $this->get(route('login'))
        ->assertSee('Login Admin — Portfolio')
        ->assertSee('Masuk ke admin.')
        ->assertSee('Kembali ke portfolio')
        ->assertSee('name="_token"', false)
        ->assertSee('autocomplete="current-password"', false);
});

test('failed login displays its error and preserves email without exposing the password', function (): void {
    $this->from(route('login'))->post(route('login'), [
        'email' => 'visitor@example.com',
        'password' => 'incorrect-test-password',
    ])->assertRedirect(route('login'))->assertSessionHasErrors('email');

    $this->assertGuest();

    $this->withCookie(config('session.cookie'), session()->getId())
        ->get(route('login'))
        ->assertSee(__('auth.failed'))
        ->assertSee('visitor@example.com')
        ->assertSee('aria-describedby="email-error"', false)
        ->assertDontSee('incorrect-test-password');
});

test('valid login authenticates and redirects directly to the admin dashboard', function (): void {
    $user = User::factory()->create();

    $this->post(route('login'), ['email' => $user->email, 'password' => 'password', 'remember' => '1'])
        ->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('home redirects authenticated users to the admin dashboard', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('home'))->assertRedirect(route('admin.dashboard'));

    $this->get(route('admin.dashboard'))->assertViewIs('admin.dashboard');
});

test('home still requires login', function (): void {
    $this->get(route('home'))->assertRedirect(route('login'));
});

test('authenticated users visiting login go directly to the admin dashboard', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('login'))->assertRedirect(route('admin.dashboard'));
});

test('login preserves a previously requested admin page', function (): void {
    $user = User::factory()->create();

    $this->withSession(['url.intended' => route('admin.skills.index')])
        ->post(route('login'), ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect(route('admin.skills.index'));

    $this->assertAuthenticatedAs($user);
});
