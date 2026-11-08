<?php

use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Database\Factories\PostFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('marketing tools require administrator login', function (): void {
    $this->get('/admin/marketing')->assertRedirect(route('login'));
    $this->post('/admin/marketing/drafts/menilai-kebutuhan-aplikasi-kasir')->assertRedirect(route('login'));
    $this->put('/admin/settings', ['ga4_measurement_id' => 'G-ABC1234567'])->assertRedirect(route('login'));

    $this->assertDatabaseCount('posts', 0);
    $this->assertDatabaseCount('settings', 0);
});

test('admin marketing guide offers draft articles and measurement instructions', function (): void {
    $this->actingAs(User::factory()->create())->get('/admin/marketing')
        ->assertSee('Enam draft artikel untuk ditinjau')
        ->assertSee('Catatan mingguan di spreadsheet')
        ->assertSee('contact_click');
});

test('article templates create unpublished editable drafts with relevant internal links', function (string $slug, string $landingPath): void {
    $this->actingAs(User::factory()->create());

    $response = $this->post('/admin/marketing/drafts/'.$slug);
    $post = Post::where('slug', $slug)->sole();

    $response->assertRedirect(route('admin.posts.edit', $post));
    expect($post->is_published)->toBeFalse();
    expect($post->published_at)->toBeNull();
    expect($post->content)->toContain('DRAFT UNTUK REVIEW', 'href="'.$landingPath.'"', 'https://wa.me/628999770477');
    $this->get(route('blog.show', $post->slug))->assertNotFound();
})->with([
    ['menilai-kebutuhan-aplikasi-kasir', '/kulapos'],
    ['aplikasi-kasir-offline-vs-online', '/kulapos'],
    ['tanda-usaha-membutuhkan-sistem-stok', '/jasa-aplikasi'],
    ['kapan-percetakan-membutuhkan-erp', '/jasa-aplikasi'],
    ['website-atau-sistem-pemesanan', '/jasa-aplikasi'],
    ['alur-digitalisasi-administrasi-usaha', '/jasa-aplikasi'],
]);

test('opening an existing template preserves owner edits and publication state', function (): void {
    $post = PostFactory::new()->create(['slug' => 'menilai-kebutuhan-aplikasi-kasir', 'content' => 'Pengalaman asli pemilik.']);
    $this->actingAs(User::factory()->create());

    $this->post('/admin/marketing/drafts/menilai-kebutuhan-aplikasi-kasir')->assertRedirect(route('admin.posts.edit', $post));

    $this->assertDatabaseCount('posts', 1);
    $this->assertDatabaseHas('posts', ['id' => $post->id, 'content' => 'Pengalaman asli pemilik.', 'is_published' => true]);
});

test('unknown article templates return 404 without writing data', function (): void {
    $this->actingAs(User::factory()->create());

    $this->post('/admin/marketing/drafts/unknown')->assertNotFound();

    $this->assertDatabaseCount('posts', 0);
});

test('settings reject invalid tracking identifiers and unsafe verification tokens', function (array $input, string $field, string $message): void {
    $this->actingAs(User::factory()->create());

    $this->put('/admin/settings', $input)->assertSessionHasErrors([$field => $message]);

    $this->assertDatabaseCount('settings', 0);
})->with([
    [['ga4_measurement_id' => 'invalid'], 'ga4_measurement_id', 'Gunakan ID pengukuran GA4 yang valid, diawali G-.'],
    [['google_site_verification' => '<meta content="injected">'], 'google_site_verification', 'Masukkan hanya token verifikasi Google, bukan tag HTML.'],
]);

test('owner can upload a screenshot and configure or disable GA4 without changing the business number', function (): void {
    Storage::fake('public');
    Setting::create(['key' => 'site_title', 'value' => 'Identitas pemilik']);
    $this->actingAs(User::factory()->create());

    $this->put('/admin/settings', [
        'kulapos_screenshot' => UploadedFile::fake()->image('kasir.png'),
        'ga4_measurement_id' => 'G-ABC1234567',
        'google_site_verification' => 'verified_owner-123',
        'whatsapp_number' => 'unapproved-number',
    ])->assertRedirect(route('admin.settings.index'))->assertSessionHasNoErrors();

    $screenshot = Setting::where('key', 'kulapos_screenshot')->sole()->value;
    Storage::disk('public')->assertExists($screenshot);
    $this->assertDatabaseHas('settings', ['key' => 'site_title', 'value' => 'Identitas pemilik']);
    $this->assertDatabaseMissing('settings', ['key' => 'whatsapp_number']);
    $this->get('/kulapos')->assertSee('/storage/'.$screenshot, false)->assertSee('id=G-ABC1234567', false);

    $this->put('/admin/settings', ['ga4_measurement_id' => ''])->assertSessionHasNoErrors();
    $this->get('/kulapos')->assertDontSee('googletagmanager.com/gtag/js')->assertSee('/storage/'.$screenshot, false);
});

test('screenshot uploads reject non-images without changing settings', function (): void {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $this->put('/admin/settings', ['kulapos_screenshot' => UploadedFile::fake()->create('not-image.txt', 1, 'text/plain')])
        ->assertSessionHasErrors('kulapos_screenshot');

    $this->assertDatabaseCount('settings', 0);
    expect(Storage::disk('public')->allFiles())->toBeEmpty();
});

test('owner can edit homepage sales copy without exposing HTML or overwriting legacy identity', function (): void {
    Setting::create(['key' => 'site_title', 'value' => 'Identitas lama']);
    $this->actingAs(User::factory()->create());

    $this->put('/admin/settings', [
        'sales_home_title' => 'Solusi Usaha | Zarnizar',
        'sales_home_headline' => 'Sistem <script>alert(1)</script>',
        'sales_home_intro' => 'Bicarakan alur usaha Anda.',
    ])->assertRedirect(route('admin.settings.index'))->assertSessionHasNoErrors();

    $this->get('/')
        ->assertSee('<title>Solusi Usaha | Zarnizar</title>', false)
        ->assertSee('Sistem &lt;script&gt;alert(1)&lt;/script&gt;', false)
        ->assertDontSee('<script>alert(1)</script>', false)
        ->assertSee('Bicarakan alur usaha Anda.');
    $this->assertDatabaseHas('settings', ['key' => 'site_title', 'value' => 'Identitas lama']);
});
