<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Imagick;
use ImagickException;

#[Signature('images:optimize {--min-kb=200 : Minimum original file size in KiB} {--dry-run : Preview savings without writing files}')]
#[Description('Create smaller WebP versions of public images while keeping the originals')]
class OptimizePublicImages extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! extension_loaded('imagick') || Imagick::queryFormats('WEBP') === []) {
            $this->error('Imagick with WebP support is required. Original images remain unchanged.');

            return self::FAILURE;
        }

        $minimumKilobytes = filter_var($this->option('min-kb'), FILTER_VALIDATE_INT);

        if ($minimumKilobytes === false || $minimumKilobytes < 0) {
            $this->error('--min-kb must be a non-negative integer.');

            return self::FAILURE;
        }

        $disk = Storage::disk('public');
        $optimizedCount = 0;
        $savedBytes = 0;
        $failedCount = 0;

        foreach ($disk->allFiles() as $path) {
            if (! in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'], true)) {
                continue;
            }

            $originalBytes = $disk->size($path);
            $optimizedPath = $path.'.webp';

            if ($originalBytes < $minimumKilobytes * 1024 || $disk->exists($optimizedPath)) {
                continue;
            }

            $contents = $disk->get($path);
            $dimensions = $contents === null ? false : @getimagesizefromstring($contents);

            if ($dimensions === false || ! in_array($dimensions[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
                $this->warn("Skipping invalid image: {$path}");
                $failedCount++;

                continue;
            }

            if ($dimensions[0] * $dimensions[1] > 40000000) {
                $this->warn("Skipping image larger than 40 megapixels: {$path}");
                $failedCount++;

                continue;
            }

            $image = new Imagick;

            try {
                $image->readImageBlob($contents);

                if ($image->getNumberImages() !== 1) {
                    $this->warn("Skipping animated image: {$path}");

                    continue;
                }

                $image->autoOrient();
                $image->transformImageColorspace(Imagick::COLORSPACE_SRGB);
                $maximumDimension = str_starts_with($path, 'sliders/') ? 640 : 1600;

                if (max($image->getImageWidth(), $image->getImageHeight()) > $maximumDimension) {
                    $image->thumbnailImage($maximumDimension, $maximumDimension, true);
                }

                $image->stripImage();
                $image->setImageFormat('webp');
                $image->setImageCompressionQuality(82);
                $optimizedContents = $image->getImageBlob();
                $optimizedBytes = strlen($optimizedContents);

                if ($optimizedBytes >= $originalBytes) {
                    continue;
                }

                if (! $this->option('dry-run') && ! $disk->put($optimizedPath, $optimizedContents)) {
                    $this->error("Could not write optimized image: {$path}");
                    $failedCount++;

                    continue;
                }

                $optimizedCount++;
                $savedBytes += $originalBytes - $optimizedBytes;
                $this->line(sprintf('%s: %.1f KiB -> %.1f KiB', $path, $originalBytes / 1024, $optimizedBytes / 1024));
            } catch (ImagickException $exception) {
                $this->error("Could not optimize {$path}: {$exception->getMessage()}");
                $failedCount++;
            } finally {
                $image->clear();
            }
        }

        $this->info(sprintf('%d images optimized; %.1f KiB saved. Originals preserved.', $optimizedCount, $savedBytes / 1024));

        return $failedCount === 0 ? self::SUCCESS : self::FAILURE;
    }
}
