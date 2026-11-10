<?php

use Database\Factories\PortfolioFactory;
use Database\Factories\PostFactory;
use Illuminate\Support\Facades\Storage;

test('invalid search returns 422 with a helpful message', function (mixed $search, string $message): void {
    $this->getJson('/?'.http_build_query(['search' => $search]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['search' => $message]);
})->with([
    'array input' => [['Laravel'], 'Kata pencarian harus berupa teks.'],
    'overlong input' => [str_repeat('a', 256), 'Kata pencarian maksimal 255 karakter.'],
]);

test('invalid browser search redirects home and displays its validation error', function (): void {
    $this->from('/')->get('/?search%5B%5D=Laravel')
        ->assertRedirect('/')
        ->assertSessionHasErrors(['search' => 'Kata pencarian harus berupa teks.']);

    $this->withCookie(config('session.cookie'), session()->getId())
        ->get('/')
        ->assertSee('Kata pencarian harus berupa teks.');
});

test('search filters public portfolios and posts while hiding draft and inactive results', function (): void {
    PortfolioFactory::new()->create(['title' => 'Laravel public project']);
    PortfolioFactory::new()->create(['title' => 'Unrelated project', 'description' => 'Other stack']);
    PortfolioFactory::new()->inactive()->create(['title' => 'Laravel private project']);
    PostFactory::new()->create(['title' => 'Laravel public article']);
    PostFactory::new()->create(['title' => 'Unrelated article', 'content' => 'Other stack']);
    PostFactory::new()->draft()->create(['title' => 'Laravel draft article']);

    $this->get('/?search=Laravel')
        ->assertSee('Laravel public project')
        ->assertSee('Laravel public article')
        ->assertDontSee('Unrelated project')
        ->assertDontSee('Unrelated article')
        ->assertDontSee('Laravel private project')
        ->assertDontSee('Laravel draft article');
});

test('zero is a valid search term and displays filtered results', function (): void {
    PortfolioFactory::new()->create(['title' => 'Project 0']);
    PortfolioFactory::new()->create(['title' => 'Other project', 'description' => 'No matching number']);

    $this->get('/?search=0')
        ->assertSee('SHOWING RESULTS FOR:')
        ->assertSee('Project 0')
        ->assertDontSee('Other project');
});

test('search with no matches displays empty results', function (): void {
    $this->get('/?search=missing-project')
        ->assertSee('AWAITING DEPLOYMENT...')
        ->assertSee('NO LOGS FOUND.');
});

test('inactive portfolio details return 404', function (): void {
    $portfolio = PortfolioFactory::new()->inactive()->create();

    $this->get(route('portfolio.show', $portfolio->slug))->assertNotFound();
});

test('active portfolio details remain visible', function (): void {
    $portfolio = PortfolioFactory::new()->create();

    $this->get(route('portfolio.show', $portfolio->slug))->assertSee($portfolio->title);
});

test('draft blog details return 404 without incrementing views', function (): void {
    $post = PostFactory::new()->draft()->create();

    $this->get(route('blog.show', $post->slug))->assertNotFound();

    $this->assertDatabaseHas('posts', ['id' => $post->id, 'views' => 0]);
});

test('published blog details remain visible and increment views', function (): void {
    $post = PostFactory::new()->create();

    $this->get(route('blog.show', $post->slug))->assertSee($post->title);

    $this->assertDatabaseHas('posts', ['id' => $post->id, 'views' => 1]);
});

test('blog archives use the publication month', function (): void {
    PostFactory::new()->create([
        'published_at' => '2026-07-15 12:00:00',
        'created_at' => '2026-01-15 12:00:00',
    ]);

    $this->get('/')->assertSee('July 2026')->assertDontSee('January 2026');
});

test('public images use WebP when available and fall back to the original otherwise', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('portfolios/optimized.png.webp', 'optimized-image');
    PortfolioFactory::new()->create(['image' => 'portfolios/optimized.png']);
    PortfolioFactory::new()->create(['image' => 'portfolios/original.png']);

    $this->get('/')
        ->assertSee('src="/storage/portfolios/optimized.png.webp"', false)
        ->assertSee('src="/storage/portfolios/original.png"', false)
        ->assertSee('loading="lazy"', false);

    Storage::disk('public')->assertExists('portfolios/optimized.png.webp');
});

test('public profile page renders with teacher bio, skills, and system logs', function (): void {
    $this->get(route('profil'))
        ->assertOk()
        ->assertSee('PROFIL GURU &amp; DEVELOPER', false)
        ->assertSee('TECH STACK')
        ->assertSee('SYSTEM LOGS (EXP)')
        ->assertSee('CURRICULUM VITAE')
        ->assertSee('Cetak CV / Simpan PDF');

    $this->get('/tentang')
        ->assertRedirect(route('profil'));
});

test('blog content automatically converts plain text URLs into clickable links without modifying existing links', function (): void {
    $content = '<p>"Bahan presentasi GTK 2026 — Perjalanan SASTI" (https://zarnizar.com/gtk2026)</p>'
        .'<p>"Website resmi" (https://sman4-tsm.sch.id/)</p>'
        .'<p>Sudah link: <a href="https://example.com" target="_blank">https://example.com</a></p>'
        .'<p>Tautan berakhiran titik: https://sman4-tsm.sch.id/.</p>'
        .'<pre><code>https://code-example.com</code></pre>';

    $post = PostFactory::new()->create([
        'content' => $content,
        'is_published' => true,
    ]);

    $this->get(route('blog.show', $post->slug))
        ->assertOk()
        ->assertSee('(<a href="https://zarnizar.com/gtk2026" target="_blank" rel="noopener noreferrer">https://zarnizar.com/gtk2026</a>)', false)
        ->assertSee('(<a href="https://sman4-tsm.sch.id/" target="_blank" rel="noopener noreferrer">https://sman4-tsm.sch.id/</a>)', false)
        ->assertSee('href="https://example.com"', false)
        ->assertSee('<code>https://code-example.com</code>', false);
});

test('visitors can like, switch reaction, and cancel reaction on blog articles', function (): void {
    $post = PostFactory::new()->create([
        'likes' => 0,
        'dislikes' => 0,
        'is_published' => true,
    ]);

    // 1. Like article via JSON API
    $response = $this->postJson(route('blog.react', $post->slug), ['type' => 'like']);
    $response->assertOk()
        ->assertJson([
            'success' => true,
            'action' => 'like',
            'likes' => 1,
            'dislikes' => 0,
            'userReaction' => 'like',
        ]);
    $this->assertDatabaseHas('posts', ['id' => $post->id, 'likes' => 1, 'dislikes' => 0]);

    // 2. Click like again -> cancels reaction (untoggle)
    $response2 = $this->postJson(route('blog.react', $post->slug), ['type' => 'like']);
    $response2->assertOk()
        ->assertJson([
            'success' => true,
            'action' => null,
            'likes' => 0,
            'dislikes' => 0,
            'userReaction' => null,
        ]);
    $this->assertDatabaseHas('posts', ['id' => $post->id, 'likes' => 0, 'dislikes' => 0]);

    // 3. Dislike article
    $response3 = $this->postJson(route('blog.react', $post->slug), ['type' => 'dislike']);
    $response3->assertOk()
        ->assertJson([
            'success' => true,
            'action' => 'dislike',
            'likes' => 0,
            'dislikes' => 1,
            'userReaction' => 'dislike',
        ]);
    $this->assertDatabaseHas('posts', ['id' => $post->id, 'likes' => 0, 'dislikes' => 1]);

    // 4. Switch from dislike to like
    $response4 = $this->postJson(route('blog.react', $post->slug), ['type' => 'like']);
    $response4->assertOk()
        ->assertJson([
            'success' => true,
            'action' => 'like',
            'likes' => 1,
            'dislikes' => 0,
            'userReaction' => 'like',
        ]);
    $this->assertDatabaseHas('posts', ['id' => $post->id, 'likes' => 1, 'dislikes' => 0]);

    // 5. Normal form post redirects back
    $this->from(route('blog.show', $post->slug))
        ->post(route('blog.react', $post->slug), ['type' => 'dislike'])
        ->assertRedirect(route('blog.show', $post->slug));
});
