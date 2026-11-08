<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('image optimization preserves originals and limits slider dimensions', function (): void {
    Storage::fake('public');
    $original = UploadedFile::fake()->image('slider.jpg', 2000, 1000);
    $originalContents = file_get_contents($original->getRealPath());
    Storage::disk('public')->put('sliders/slider.jpg', $originalContents);

    $this->artisan('images:optimize', ['--min-kb' => 0])->assertSuccessful();

    Storage::disk('public')->assertExists('sliders/slider.jpg.webp');
    expect(Storage::disk('public')->get('sliders/slider.jpg'))->toBe($originalContents);
    $optimized = Storage::disk('public')->get('sliders/slider.jpg.webp');
    $dimensions = getimagesizefromstring($optimized);
    expect($dimensions[0])->toBe(640);
    expect($dimensions[1])->toBe(320);
    expect($dimensions[2])->toBe(IMAGETYPE_WEBP);
    expect(strlen($optimized))->toBeLessThan(strlen($originalContents));
});

test('image optimization does not upscale small images', function (): void {
    Storage::fake('public');
    $original = UploadedFile::fake()->image('project.jpg', 150, 80);
    Storage::disk('public')->put('portfolios/project.jpg', file_get_contents($original->getRealPath()));

    $this->artisan('images:optimize', ['--min-kb' => 0])->assertSuccessful();

    Storage::disk('public')->assertExists('portfolios/project.jpg.webp');
    $dimensions = getimagesizefromstring(Storage::disk('public')->get('portfolios/project.jpg.webp'));
    expect($dimensions[0])->toBe(150);
    expect($dimensions[1])->toBe(80);
});

test('dry run previews savings without writing optimized images', function (): void {
    Storage::fake('public');
    $original = UploadedFile::fake()->image('preview.jpg', 1000, 1000);
    $originalContents = file_get_contents($original->getRealPath());
    Storage::disk('public')->put('portfolios/preview.jpg', $originalContents);

    $this->artisan('images:optimize', ['--min-kb' => 0, '--dry-run' => true])->assertSuccessful();

    Storage::disk('public')->assertMissing('portfolios/preview.jpg.webp');
    expect(Storage::disk('public')->get('portfolios/preview.jpg'))->toBe($originalContents);
});

test('image optimization skips small files and existing variants', function (): void {
    Storage::fake('public');
    $original = UploadedFile::fake()->image('small.jpg', 100, 100);
    Storage::disk('public')->put('portfolios/small.jpg', file_get_contents($original->getRealPath()));
    Storage::disk('public')->put('portfolios/existing.jpg', file_get_contents($original->getRealPath()));
    Storage::disk('public')->put('portfolios/existing.jpg.webp', 'existing-variant');

    $this->artisan('images:optimize')->assertSuccessful();

    Storage::disk('public')->assertMissing('portfolios/small.jpg.webp');
    expect(Storage::disk('public')->get('portfolios/existing.jpg.webp'))->toBe('existing-variant');
});

test('invalid images are reported without changing the original', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('portfolios/invalid.png', 'not-an-image');

    $this->artisan('images:optimize', ['--min-kb' => 0])->assertFailed();

    Storage::disk('public')->assertMissing('portfolios/invalid.png.webp');
    expect(Storage::disk('public')->get('portfolios/invalid.png'))->toBe('not-an-image');
});

test('image optimization rejects an invalid minimum size', function (): void {
    $this->artisan('images:optimize', ['--min-kb' => -1])->assertFailed();
});
