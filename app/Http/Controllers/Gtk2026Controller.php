<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Vite;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Gtk2026Controller extends Controller
{
    private const EVIDENCE_UPLOAD_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'mp4'];

    public function index(): Response
    {
        return response()->view('gtk2026.presentation', $this->content(false))
            ->header('X-GTK-Public-Shell', '1');
    }

    public function evidence(): Response
    {
        return response()->view('gtk2026.evidence', $this->content(true))
            ->header('Cache-Control', 'private, no-store')
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    public function presenter(): Response
    {
        return response()->view('gtk2026.presentation', $this->content(true))
            ->header('Cache-Control', 'private, no-store')
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    public function media(string $evidence): BinaryFileResponse
    {
        $item = config('gtk2026.evidence.'.$evidence);
        abort_unless(is_array($item) && ($item['public_approved'] ?? false) === true, 404);
        $item['file'] = $this->uploadedEvidenceFile($evidence) ?? $item['file'];

        return $this->evidenceFile($item);
    }

    public function privateMedia(string $evidence): BinaryFileResponse
    {
        $item = config('gtk2026.evidence.'.$evidence);
        abort_unless(is_array($item), 404);
        $item['file'] = $this->uploadedEvidenceFile($evidence) ?? $item['file'];

        return $this->evidenceFile($item);
    }

    public function uploadEvidence(Request $request, string $evidence): RedirectResponse
    {
        $items = config('gtk2026.evidence', []);
        $item = $items[$evidence] ?? null;
        abort_unless(is_array($item) && ! in_array($item['kind'] ?? null, ['timeline', 'youtube'], true), 404);

        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf,mp4', 'extensions:jpg,jpeg,png,webp,pdf,mp4', 'max:20480'],
        ], [
            'file.required' => 'Pilih file bukti terlebih dahulu.',
            'file.mimes' => 'File harus berupa gambar, PDF, atau video MP4.',
            'file.extensions' => 'Ekstensi file harus JPG, JPEG, PNG, WEBP, PDF, atau MP4.',
            'file.max' => 'Ukuran file maksimal 20 MB.',
        ]);

        $file = $validated['file'];
        $extension = strtolower($file->getClientOriginalExtension());
        $disk = Storage::disk('local');
        $storedPath = $file->storeAs(
            'gtk2026/evidence',
            'admin-upload-'.$evidence.'.'.$extension,
            'local',
        );

        if ($storedPath === false) {
            throw new RuntimeException('Unable to store the uploaded GTK evidence file.');
        }

        foreach (self::EVIDENCE_UPLOAD_EXTENSIONS as $existingExtension) {
            $existingPath = 'gtk2026/evidence/admin-upload-'.$evidence.'.'.$existingExtension;

            if ($existingPath !== $storedPath && $disk->exists($existingPath) && ! $disk->delete($existingPath)) {
                throw new RuntimeException('Unable to replace the previous GTK evidence file.');
            }
        }

        return redirect()->route('gtk2026.evidence')
            ->with('status', 'Bukti "'.$item['title'].'" berhasil diunggah.');
    }

    public function worker(): Response
    {
        abort_if(Vite::isRunningHot(), 404);
        $core = [route('gtk2026', [], false), Vite::asset('resources/css/gtk2026.css'), Vite::asset('resources/js/gtk2026.js')];

        foreach (config('gtk2026.assets') as $path) {
            if ($this->publicAsset($path) !== null) {
                $core[] = asset($path);
            }
        }

        return response('const GTK_CORE = '.json_encode($core, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES).";\n".file_get_contents(resource_path('js/gtk2026-worker.js')))
            ->header('Content-Type', 'text/javascript; charset=UTF-8')
            ->header('Service-Worker-Allowed', route('gtk2026', [], false))
            ->header('Cache-Control', 'no-cache');
    }

    /**
     * @return array{gtk: array<string, mixed>, evidence: array<string, array<string, mixed>>, slideEvidence: array<string, array<int, array<string, mixed>>>, private: bool}
     */
    private function content(bool $private): array
    {
        $gtk = config('gtk2026');
        $evidence = [];

        foreach ($gtk['evidence'] as $id => $item) {
            $file = $this->uploadedEvidenceFile($id) ?? $item['file'];
            $path = $this->resolveEvidenceFile($file);
            $available = $path !== null && ($private || ($item['public_approved'] ?? false) === true);
            $videos = $this->youtubeVideos($item, $private);
            $evidence[$id] = [
                'title' => $item['title'], 'category' => $item['category'], 'icon' => $item['icon'],
                'summary' => $item['summary'], 'kind' => $item['kind'] ?? 'media',
                'available' => $available || $videos !== [],
                'url' => $available ? route($private ? 'gtk2026.evidence.media' : 'gtk2026.media', $id) : null,
                'type' => $available ? mime_content_type($path) : null,
                'videos' => $videos,
            ];
        }

        unset($gtk['evidence']);
        $gtk['assets'] = array_map(fn (?string $path): ?string => $this->publicAsset($path), $gtk['assets']);

        $slideEvidence = [];

        foreach ($gtk['slide_evidence'] as $slide => $ids) {
            $slideEvidence[$slide] = [];

            foreach ($ids as $id) {
                $item = $evidence[$id] ?? null;

                if ($item !== null && $item['available'] && str_starts_with($item['type'] ?? '', 'image/')) {
                    $slideEvidence[$slide][] = ['id' => $id, ...$item];
                }
            }
        }

        return compact('gtk', 'evidence', 'slideEvidence', 'private');
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<int, array{title: string, url: string, embed_url: string, start: string}>
     */
    private function youtubeVideos(array $item, bool $private): array
    {
        if (($item['kind'] ?? null) !== 'youtube' || (! $private && ($item['public_approved'] ?? false) !== true)) {
            return [];
        }

        $videos = [];

        foreach ($item['videos'] ?? [] as $video) {
            $id = $video['id'] ?? null;
            $start = $video['start_seconds'] ?? 0;
            $title = $video['title'] ?? null;

            if (! is_string($id) || ! preg_match('/^[a-zA-Z0-9_-]{11}$/', $id) || ! is_int($start) || $start < 0 || $start > 86400 || ! is_string($title)) {
                continue;
            }

            $videos[] = [
                'title' => $title,
                'url' => 'https://www.youtube.com/watch?v='.$id.'&t='.$start.'s',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/'.$id.'?start='.$start.'&rel=0&playsinline=1',
                'start' => sprintf('%02d:%02d', intdiv($start, 60), $start % 60),
            ];
        }

        return $videos;
    }

    private function publicAsset(?string $path): ?string
    {
        if ($path === null || ! preg_match('#^assets/gtk2026/[a-zA-Z0-9/_-]+\.(webp|png|jpe?g)$#', $path)) {
            return null;
        }

        return is_file(public_path($path)) ? asset($path) : null;
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function evidenceFile(array $item): BinaryFileResponse
    {
        $path = $this->resolveEvidenceFile($item['file']);
        abort_if($path === null, 404);

        return response()->file($path, [
            'X-Content-Type-Options' => 'nosniff',
            'X-Robots-Tag' => 'noindex, nofollow',
            'Cache-Control' => 'private, no-store',
            'Content-Security-Policy' => "default-src 'none'; media-src 'self'; style-src 'unsafe-inline'; sandbox",
        ]);
    }

    private function resolveEvidenceFile(?string $file): ?string
    {
        if ($file === null || ! preg_match('/^[a-zA-Z0-9_-]+\.(webp|png|jpe?g|pdf|mp4)$/', $file)) {
            return null;
        }

        $root = realpath(Storage::disk('local')->path('gtk2026/evidence'));
        $path = realpath(Storage::disk('local')->path('gtk2026/evidence/'.$file));

        if ($root === false || $path === false || ! str_starts_with($path, $root.DIRECTORY_SEPARATOR) || ! is_file($path)) {
            return null;
        }

        $allowedTypes = ['image/webp', 'image/png', 'image/jpeg', 'application/pdf', 'video/mp4'];

        return in_array(mime_content_type($path), $allowedTypes, true) ? $path : null;
    }

    private function uploadedEvidenceFile(string $evidence): ?string
    {
        $disk = Storage::disk('local');

        foreach (self::EVIDENCE_UPLOAD_EXTENSIONS as $extension) {
            $file = 'admin-upload-'.$evidence.'.'.$extension;

            if ($disk->exists('gtk2026/evidence/'.$file)) {
                return $file;
            }
        }

        return null;
    }
}
