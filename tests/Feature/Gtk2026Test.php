<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function storeGtkEvidence(string $file = 'dashboard.png'): void
{
    Storage::fake('local');
    Storage::disk('local')->put('gtk2026/evidence/'.$file, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aDVUAAAAASUVORK5CYII='));
    config(['gtk2026.evidence.dashboard.file' => $file]);
}

test('GTK renders eight readable slides without requiring a database or login', function (): void {
    $response = $this->get(route('gtk2026'))
        ->assertOk()
        ->assertHeader('X-GTK-Public-Shell', '1')
        ->assertSeeText('Nizar Suryadi, S.T.')
        ->assertSeeText('1.425')
        ->assertSeeText('Per 7 November 2026')
        ->assertSeeText('89')
        ->assertSeeText('1.338')
        ->assertSeeText('Belum tersedia')
        ->assertSeeText('Siaga Patas untuk Patas Teratas, Patas Berkualitas');

    expect(substr_count($response->getContent(), ' data-slide data-label='))->toBe(8);
    expect(array_sum(array_column(config('gtk2026.slides'), 'seconds')))->toBe(360);
});

test('presentation and notes URLs retain the complete public shell', function (string $query): void {
    $this->get(route('gtk2026').$query)
        ->assertSeeText('Pengabdian Harus Tetap Berjalan')
        ->assertSee('data-notes hidden', false)
        ->assertDontSee('sales-navigation');
})->with(['?present=1', '?notes=1', '?present=1&notes=1']);

test('unapproved evidence never exposes its filename or media URL publicly', function (): void {
    storeGtkEvidence('private-student-record.png');
    config(['gtk2026.evidence.dashboard.public_approved' => false]);

    $this->get(route('gtk2026'))
        ->assertDontSee('private-student-record.png')
        ->assertDontSee(route('gtk2026.media', 'dashboard'));
    $this->get(route('gtk2026.media', 'dashboard'))->assertNotFound();
});

test('approved evidence is available through the allowlisted public media route', function (): void {
    storeGtkEvidence();
    config(['gtk2026.evidence.dashboard.public_approved' => true]);

    $this->get(route('gtk2026'))
        ->assertSee('data-src="'.route('gtk2026.media', 'dashboard').'"', false);
    $this->get(route('gtk2026.media', 'dashboard'))
        ->assertOk()
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
});

test('private evidence index and media require the existing admin login', function (string $path): void {
    $this->get($path)->assertRedirect(route('login'));
})->with(['/gtk2026/evidence', '/gtk2026/evidence/dashboard/media', '/gtk2026/presenter?present=1']);

test('guests cannot upload GTK evidence', function (): void {
    $this->post(route('gtk2026.evidence.upload', 'source'), [
        'file' => UploadedFile::fake()->image('source.png'),
    ])->assertRedirect(route('login'));
});

test('authenticated administrators can upload private evidence without publishing it', function (): void {
    Storage::fake('local');
    $file = UploadedFile::fake()->image('source.png');

    $this->actingAs(User::factory()->create())
        ->post(route('gtk2026.evidence.upload', 'source'), ['file' => $file])
        ->assertRedirect(route('gtk2026.evidence'))
        ->assertSessionHas('status');

    Storage::disk('local')->assertExists('gtk2026/evidence/admin-upload-source.png');
    $this->get(route('gtk2026.evidence'))
        ->assertSee(route('gtk2026.evidence.media', 'source'));
    $this->get(route('gtk2026.presenter'))
        ->assertSee('data-src="'.route('gtk2026.evidence.media', 'source').'"', false);
    $this->get(route('gtk2026.evidence.media', 'source'))->assertOk();
    $this->get(route('gtk2026'))->assertDontSee(route('gtk2026.media', 'source'));
    $this->get(route('gtk2026.media', 'source'))->assertNotFound();
});

test('evidence uploads reject unsupported files and non-uploadable evidence types', function (): void {
    Storage::fake('local');
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('gtk2026.evidence.upload', 'source'), [
            'file' => UploadedFile::fake()->createWithContent('source.php', '<?php echo "unsafe";'),
        ])
        ->assertSessionHasErrors('file');

    Storage::disk('local')->assertMissing('gtk2026/evidence/admin-upload-source.php');
    $this->actingAs($user)
        ->post(route('gtk2026.evidence.upload', 'timeline'), [
            'file' => UploadedFile::fake()->image('timeline.png'),
        ])
        ->assertNotFound();
    $this->actingAs($user)
        ->post(route('gtk2026.evidence.upload', 'unknown'), [
            'file' => UploadedFile::fake()->image('unknown.png'),
        ])
        ->assertNotFound();
});

test('the authenticated presentation embeds documentation in its slides without enabling offline caching', function (): void {
    storeGtkEvidence();
    $placements = [
        3 => ['sastiearly', 'siagaearly', 'classroomsupport', 'classroomdevices'],
        4 => ['publication', 'ministrypresentation', 'contributor'],
        5 => ['dashboard', 'mobile', 'learning', 'asmaulhusna', 'tadarus', 'alumni'],
        6 => ['pilketos', 'pilketosarchive', 'pilketospresentation', 'pilketosactivity'],
    ];

    foreach ($placements as $ids) {
        foreach ($ids as $id) {
            config(['gtk2026.evidence.'.$id.'.file' => 'dashboard.png']);
        }
    }

    $response = $this->actingAs(User::factory()->create())
        ->get(route('gtk2026.presenter', ['present' => 1]))
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
        ->assertHeaderContains('Cache-Control', 'no-store')
        ->assertHeaderMissing('X-GTK-Public-Shell')
        ->assertSee('data-worker=""', false);

    $document = new DOMDocument;
    @$document->loadHTML($response->getContent());
    $xpath = new DOMXPath($document);
    expect($xpath->query('//section[@data-slide]')->length)->toBe(8);

    foreach ($placements as $number => $ids) {
        foreach ($ids as $id) {
            $url = route('gtk2026.evidence.media', $id);
            expect($xpath->query('//section[@id="slide-'.$number.'"]//img[@src="'.$url.'"]')->length)->toBe(1);
        }
    }

    config(['gtk2026.evidence.pilketos.public_approved' => false]);
    $this->get(route('gtk2026'))
        ->assertDontSee('/gtk2026/evidence/pilketos/media')
        ->assertDontSee('gtk-documentation-pilketos');
});

test('missing and non-image evidence are omitted from the inline slide gallery', function (): void {
    Storage::fake('local');
    Storage::disk('local')->put('gtk2026/evidence/cover.pdf', "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF");
    config([
        'gtk2026.evidence.publication.file' => 'cover.pdf',
        'gtk2026.evidence.learning.file' => 'missing.png',
    ]);

    $this->actingAs(User::factory()->create())->get(route('gtk2026.presenter'))
        ->assertDontSee('gtk-documentation-publication')
        ->assertDontSee('gtk-documentation-learning')
        ->assertSee('Buka dokumen PDF');
});

test('authenticated administrators can see private evidence with no-store and noindex headers', function (): void {
    storeGtkEvidence();
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('gtk2026.evidence'))
        ->assertSee(route('gtk2026.evidence.media', 'dashboard'))
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
        ->assertHeaderContains('Cache-Control', 'no-store');
    $this->actingAs($user)->get(route('gtk2026.evidence.media', 'dashboard'))
        ->assertOk()
        ->assertHeaderContains('Cache-Control', 'no-store');
});

test('missing evidence never renders a broken image or returns a file', function (): void {
    config(['gtk2026.evidence.dashboard.file' => 'missing.png', 'gtk2026.evidence.dashboard.public_approved' => true]);

    $this->get(route('gtk2026'))->assertDontSee('data-src="'.route('gtk2026.media', 'dashboard').'"', false);
    $this->get(route('gtk2026.media', 'dashboard'))->assertNotFound();
    $this->get('/gtk2026/media/unknown')->assertNotFound();
});

test('evidence rejects path traversal and active content', function (string $file, string $contents): void {
    Storage::fake('local');
    Storage::disk('local')->put('gtk2026/evidence/unsafe.png', $contents);
    config(['gtk2026.evidence.dashboard.file' => $file, 'gtk2026.evidence.dashboard.public_approved' => true]);

    $this->get(route('gtk2026.media', 'dashboard'))->assertNotFound();
})->with([
    'path traversal' => ['../unsafe.png', 'not an image'],
    'HTML posing as image' => ['unsafe.png', '<html><script>alert(1)</script></html>'],
    'SVG active content' => ['unsafe.svg', '<svg onload="alert(1)"></svg>'],
]);

test('evidence cannot escape its storage root through a symlink', function (): void {
    Storage::fake('local');
    Storage::disk('local')->put('outside.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aDVUAAAAASUVORK5CYII='));
    Storage::disk('local')->makeDirectory('gtk2026/evidence');
    symlink(Storage::disk('local')->path('outside.png'), Storage::disk('local')->path('gtk2026/evidence/escape.png'));
    config(['gtk2026.evidence.dashboard.file' => 'escape.png', 'gtk2026.evidence.dashboard.public_approved' => true]);

    $this->get(route('gtk2026.media', 'dashboard'))->assertNotFound();
});

test('GTK escapes configured text and ignores arbitrary file query parameters', function (): void {
    config(['gtk2026.evidence.dashboard.title' => '<script>alert("unsafe")</script>']);

    $this->get('/gtk2026?file=../../.env')
        ->assertSee('&lt;script&gt;', false)
        ->assertDontSee('<script>alert("unsafe")</script>', false);
});

test('offline worker is scoped to GTK and includes only public core assets', function (): void {
    $this->get(route('gtk2026.worker'))
        ->assertOk()
        ->assertHeader('Content-Type', 'text/javascript; charset=UTF-8')
        ->assertHeader('Service-Worker-Allowed', '/gtk2026')
        ->assertSee('X-GTK-Public-Shell')
        ->assertDontSee('/gtk2026/evidence');
});

test('presentation embeds profile and testimonials in their slides with the requested start times', function (): void {
    $response = $this->get(route('gtk2026'))
        ->assertSeeText('Video testimoni')
        ->assertSeeText('Testimoni guru dan siswa')
        ->assertSeeText('Testimoni pimpinan')
        ->assertSeeText('Mulai 00:16')
        ->assertSeeText('Mulai 00:08')
        ->assertSeeText('Profil SASTI — Nizar Suryadi')
        ->assertSeeText('Mulai 00:07')
        ->assertSee('aria-controls="ecosystem-video"', false)
        ->assertSee('href="https://www.youtube.com/watch?v=hszFbQBkFjM&amp;t=7s" target="_blank" rel="noopener noreferrer"', false)
        ->assertSee('href="https://www.youtube.com/watch?v=xXtA_eOoEZo&amp;t=16s" target="_blank" rel="noopener noreferrer"', false)
        ->assertSee('href="https://www.youtube.com/watch?v=kdJpUBJoAtg&amp;t=8s" target="_blank" rel="noopener noreferrer"', false)
        ->assertSee('data-embed-src="https://www.youtube-nocookie.com/embed/hszFbQBkFjM?start=7&amp;rel=0&amp;playsinline=1"', false)
        ->assertSee('data-embed-src="https://www.youtube-nocookie.com/embed/xXtA_eOoEZo?start=16&amp;rel=0&amp;playsinline=1"', false)
        ->assertSee('data-embed-src="https://www.youtube-nocookie.com/embed/kdJpUBJoAtg?start=8&amp;rel=0&amp;playsinline=1"', false);

    $document = new DOMDocument;
    @$document->loadHTML($response->getContent());
    $xpath = new DOMXPath($document);
    expect($xpath->query('//section[@id="slide-5"]//iframe')->length)->toBe(1);
    expect($xpath->query('//section[@id="slide-6"]//iframe')->length)->toBe(2);
    expect($xpath->query('//iframe[@src]')->length)->toBe(0);

    $this->get(route('gtk2026.media', 'testimonials'))->assertNotFound();
});

test('unapproved testimonial links remain available only in the authenticated evidence view', function (): void {
    config(['gtk2026.evidence.testimonials.public_approved' => false]);

    $this->get(route('gtk2026'))
        ->assertDontSee('www.youtube.com/watch?v=xXtA_eOoEZo')
        ->assertDontSee('www.youtube.com/watch?v=kdJpUBJoAtg');
    $this->actingAs(User::factory()->create())->get(route('gtk2026.evidence'))
        ->assertSee('https://www.youtube.com/watch?v=xXtA_eOoEZo&amp;t=16s', false)
        ->assertSee('https://www.youtube.com/watch?v=kdJpUBJoAtg&amp;t=8s', false);
});

test('testimonial links reject invalid video identifiers and timestamps', function (array $video): void {
    config(['gtk2026.evidence.testimonials.videos' => [$video]]);

    $this->get(route('gtk2026'))->assertDontSee('aria-label="Video Suara dari sekolah"', false);
})->with([
    'external URL instead of video ID' => [['id' => 'https://example.com/unsafe', 'title' => 'Test video', 'start_seconds' => 0]],
    'negative timestamp' => [['id' => 'xXtA_eOoEZo', 'title' => 'Test video', 'start_seconds' => -1]],
    'non-integer timestamp' => [['id' => 'xXtA_eOoEZo', 'title' => 'Test video', 'start_seconds' => '16']],
]);

test('testimonial titles are escaped and external videos are omitted from image galleries', function (): void {
    config([
        'gtk2026.evidence.testimonials.videos.0.title' => '<script>alert("unsafe")</script>',
        'gtk2026.slide_evidence.journey' => ['testimonials'],
    ]);

    $this->get(route('gtk2026'))
        ->assertSee('&lt;script&gt;', false)
        ->assertDontSee('<script>alert("unsafe")</script>', false)
        ->assertDontSee('gtk-documentation-testimonials');
});
