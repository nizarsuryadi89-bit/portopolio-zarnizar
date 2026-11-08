<?php

use App\Models\Setting;
use Database\Factories\PortfolioFactory;
use Database\Factories\PostFactory;
use Illuminate\Support\Facades\Storage;

test('public sales pages have canonical SEO and no analytics until configured', function (string $path, string $title): void {
    $this->get($path.'?utm_source=preview')
        ->assertSee('<title>'.$title.'</title>', false)
        ->assertSee('<link rel="canonical" href="'.url($path).'">', false)
        ->assertSee('<meta name="robots" content="index, follow">', false)
        ->assertSee('property="og:description"', false)
        ->assertSee('name="twitter:card"', false)
        ->assertDontSee('googletagmanager.com/gtag/js');
})->with([
    ['/', 'Zarnizar — Developer Aplikasi &amp; Solusi Digital Tasikmalaya'],
    ['/kulapos', 'KulaPOS — Aplikasi Kasir Offline UMKM | Zarnizar'],
    ['/jasa-aplikasi', 'Jasa Pembuatan Aplikasi Web Tasikmalaya | Zarnizar'],
]);

test('WhatsApp sales links use the agreed number and product-specific messages', function (string $path, string $product, string $message): void {
    $response = $this->get($path);
    $document = new DOMDocument;
    $document->loadHTML('<?xml encoding="UTF-8">'.$response->getContent(), LIBXML_NOERROR | LIBXML_NOWARNING);
    $links = (new DOMXPath($document))->query('//a[@data-product="'.$product.'"]');

    expect($links->length)->toBeGreaterThan(0);
    foreach ($links as $link) {
        $url = parse_url($link->getAttribute('href'));
        parse_str($url['query'], $query);
        expect($url['scheme'])->toBe('https');
        expect($url['host'])->toBe('wa.me');
        expect($url['path'])->toBe('/628999770477');
        expect($query['text'])->toBe($message);
        expect($link->getAttribute('data-lead-source'))->toBe($product);
    }
})->with([
    ['/kulapos', 'kulapos', 'Halo, saya melihat KulaPOS di zarnizar.com. Saya ingin demo dan informasi paketnya.'],
    ['/jasa-aplikasi', 'jasa-aplikasi', 'Halo, saya melihat layanan pembuatan aplikasi di zarnizar.com. Saya ingin konsultasi kebutuhan sistem untuk usaha saya.'],
]);

test('sales pages recommend only relevant published articles', function (string $path, string $keyword): void {
    PostFactory::new()->create(['title' => $keyword.' panduan usaha']);
    PostFactory::new()->draft()->create(['title' => $keyword.' draft rahasia']);
    PostFactory::new()->create(['title' => 'Perjalanan akhir pekan']);

    $this->get($path)
        ->assertSee($keyword.' panduan usaha')
        ->assertDontSee($keyword.' draft rahasia')
        ->assertDontSee('Perjalanan akhir pekan');
})->with([['/kulapos', 'Kasir'], ['/jasa-aplikasi', 'Inventori sistem']]);

test('service proof shows active public portfolios and escapes their titles', function (): void {
    $project = PortfolioFactory::new()->create(['title' => 'Sistem <script>alert(1)</script>']);
    PortfolioFactory::new()->inactive()->create(['title' => 'Private project']);

    $this->get('/jasa-aplikasi')
        ->assertSee($project->title)
        ->assertSee(route('portfolio.show', $project->slug))
        ->assertDontSee($project->title, false)
        ->assertDontSee('Private project');
});

test('KulaPOS shows uploaded screenshots without requiring a primary screenshot', function (): void {
    Storage::fake('public');
    Setting::create(['key' => 'kulapos_screenshot_secondary', 'value' => 'settings/kasir.png']);

    $this->get('/kulapos')->assertSee('src="/storage/settings/kasir.png"', false);
});

test('sitemap includes sales pages and published content while excluding drafts and private projects', function (): void {
    $post = PostFactory::new()->create();
    $draft = PostFactory::new()->draft()->create();
    $project = PortfolioFactory::new()->create();
    $privateProject = PortfolioFactory::new()->inactive()->create();

    $response = $this->get('/sitemap.xml')
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
    $document = simplexml_load_string($response->getContent());
    $locations = array_map(fn ($url): string => (string) $url->loc, iterator_to_array($document->url, false));

    expect($locations)->toContain(url('/'), url('/kulapos'), url('/jasa-aplikasi'), route('blog.show', $post->slug), route('portfolio.show', $project->slug))
        ->not->toContain(route('blog.show', $draft->slug), route('portfolio.show', $privateProject->slug), url('/login'), url('/admin/marketing'));
});

test('valid GA4 configuration loads once per page and search pages remain noindex', function (): void {
    Setting::create(['key' => 'ga4_measurement_id', 'value' => 'G-ABC1234567']);
    Setting::create(['key' => 'google_site_verification', 'value' => 'owner_token-123']);

    $response = $this->get('/?search=kasir')
        ->assertSee('id=G-ABC1234567', false)
        ->assertSee('<meta name="google-site-verification" content="owner_token-123">', false)
        ->assertSee('<meta name="robots" content="noindex, follow">', false);

    expect(substr_count($response->getContent(), 'googletagmanager.com/gtag/js'))->toBe(1);
});

test('invalid legacy GA4 values never produce a tracking script', function (): void {
    Setting::create(['key' => 'ga4_measurement_id', 'value' => '"><script>alert(1)</script>']);

    $this->get('/kulapos')->assertDontSee('googletagmanager.com/gtag/js')->assertDontSee('<script>alert(1)</script>', false);
});
