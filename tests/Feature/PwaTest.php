<?php

use App\Models\User;

test('the install manifest uses portable URLs and correctly sized app icons', function (): void {
    $response = $this->get(route('pwa.manifest'))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/manifest+json');
    $manifest = $response->json();

    expect($manifest['display'])->toBe('standalone');
    expect($manifest['start_url'])->toBe('./admin/dashboard');
    expect($manifest['scope'])->toBe('./');
    expect(parse_url($manifest['start_url'], PHP_URL_HOST))->toBeNull();
    expect(array_column($manifest['icons'], 'sizes'))->toContain('192x192', '512x512');

    foreach ($manifest['icons'] as $icon) {
        $dimensions = getimagesize(public_path(ltrim($icon['src'], './')));
        expect($dimensions[0].'x'.$dimensions[1])->toBe($icon['sizes']);
        expect($dimensions[2])->toBe(IMAGETYPE_PNG);
    }
});

test('the mobile content hub requires authentication', function (): void {
    $this->get(route('admin.content'))->assertRedirect(route('login'));
});

test('the mobile content hub provides access to content modules and profile', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('admin.content'))
        ->assertSee('Konten Anda')
        ->assertSee(route('admin.portfolios.index'))
        ->assertSee(route('admin.posts.index'))
        ->assertSee(route('admin.activities.index'))
        ->assertSee(route('admin.sliders.index'))
        ->assertSee(route('admin.profile.edit'))
        ->assertSee('Navigasi aplikasi')
        ->assertSee('app.webmanifest');
});

test('login provides an install entry point without requiring an existing session', function (): void {
    $this->get(route('login'))
        ->assertSee('app.webmanifest')
        ->assertSee('service-worker.js')
        ->assertSee('Pasang aplikasi');
});
