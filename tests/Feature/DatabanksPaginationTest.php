<?php

use Database\Factories\PostFactory;

test('databanks shows six published articles per page in a stable order', function (int $page, array $visible, array $hidden, string $range): void {
    foreach (range(1, 8) as $number) {
        PostFactory::new()->create([
            'title' => 'Artikel '.$number,
            'published_at' => '2026-09-01 12:00:00',
        ]);
    }
    PostFactory::new()->draft()->create(['title' => 'Artikel draft']);

    $response = $this->get('/?blog_page='.$page);

    $response->assertSee($visible)
        ->assertDontSee($hidden)
        ->assertDontSee('Artikel draft')
        ->assertSee($range)
        ->assertSee('Uncategorized')
        ->assertSee('September 2026')
        ->assertSee('aria-label="Navigasi Databanks"', false)
        ->assertSee('aria-current="page"', false);
})->with([
    'first page' => [1, ['Artikel 8', 'Artikel 7', 'Artikel 6', 'Artikel 5', 'Artikel 4', 'Artikel 3'], ['Artikel 2', 'Artikel 1'], 'Menampilkan 1–6 dari 8 artikel'],
    'last page' => [2, ['Artikel 2', 'Artikel 1'], ['Artikel 8', 'Artikel 7', 'Artikel 6', 'Artikel 5', 'Artikel 4', 'Artikel 3'], 'Menampilkan 7–8 dari 8 artikel'],
]);

test('databanks pagination preserves the search and returns to the blog section', function (): void {
    PostFactory::new()->count(7)->create(['title' => 'Laravel article', 'content' => 'Matching article']);
    PostFactory::new()->count(8)->create(['title' => 'Unrelated article', 'content' => 'Other stack']);
    PostFactory::new()->draft()->create(['title' => 'Laravel draft article']);

    $this->get('/?search=Laravel')
        ->assertSee('Menampilkan 1–6 dari 7 artikel')
        ->assertSee('?search=Laravel&amp;blog_page=2#blog', false)
        ->assertDontSee('Unrelated article')
        ->assertDontSee('Laravel draft article');

    $this->get('/?search=Laravel&blog_page=2')
        ->assertSee('Menampilkan 7–7 dari 7 artikel')
        ->assertSee('Laravel article')
        ->assertSee('?search=Laravel&amp;blog_page=1#blog', false)
        ->assertDontSee('Unrelated article');
});

test('databanks does not show pagination controls for a single page', function (): void {
    PostFactory::new()->count(2)->create();

    $this->get('/')
        ->assertSee('Menampilkan 1–2 dari 2 artikel')
        ->assertDontSee('aria-label="Navigasi Databanks"', false);
});

test('an unavailable databanks page still lets the visitor select an existing page', function (): void {
    PostFactory::new()->count(7)->create();

    $this->get('/?blog_page=99')
        ->assertSee('NO LOGS FOUND.')
        ->assertSee('7 artikel tersedia. Pilih halaman lain.')
        ->assertSee('?blog_page=1#blog', false);
});
