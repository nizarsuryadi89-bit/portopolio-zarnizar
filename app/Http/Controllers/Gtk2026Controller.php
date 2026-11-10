<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Vite;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

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

    public function media(Request $request, string $evidence): BinaryFileResponse
    {
        $item = config('gtk2026.evidence.'.$evidence);
        abort_unless(is_array($item) && ($item['public_approved'] ?? false) === true, 404);

        $requestedFile = $request->query('file');
        if ($requestedFile) {
            $allowedFiles = $this->uploadedEvidenceFiles($evidence);
            abort_unless(in_array($requestedFile, $allowedFiles, true) || $requestedFile === ($item['file'] ?? null), 404);
            $item['file'] = $requestedFile;
        } else {
            $item['file'] = $this->uploadedEvidenceFile($evidence) ?? $item['file'];
        }

        return $this->evidenceFile($item);
    }

    public function privateMedia(Request $request, string $evidence): BinaryFileResponse
    {
        $item = config('gtk2026.evidence.'.$evidence);
        abort_unless(is_array($item), 404);

        $requestedFile = $request->query('file');
        if ($requestedFile) {
            $allowedFiles = $this->uploadedEvidenceFiles($evidence);
            abort_unless(in_array($requestedFile, $allowedFiles, true) || $requestedFile === ($item['file'] ?? null), 404);
            $item['file'] = $requestedFile;
        } else {
            $item['file'] = $this->uploadedEvidenceFile($evidence) ?? $item['file'];
        }

        return $this->evidenceFile($item);
    }

    public function uploadEvidence(Request $request, string $evidence): RedirectResponse
    {
        $items = config('gtk2026.evidence', []);
        $item = $items[$evidence] ?? null;
        abort_unless(is_array($item) && ! in_array($item['kind'] ?? null, ['timeline', 'youtube'], true), 404);

        $contentLength = (int) $request->server('CONTENT_LENGTH', 0);
        if ($contentLength > 0 && empty($request->all()) && empty($request->allFiles())) {
            $postLimit = ini_get('post_max_size') ?: 'server';

            return back()->withErrors([
                'files' => 'Ukuran total file melebihi batas server (post_max_size: '.$postLimit.'). Silakan kompres file atau naikkan post_max_size di cPanel/php.ini.',
            ]);
        }

        $filesBag = $request->files->get('files') ?? $request->files->get('file');
        $rawUploadedFiles = is_array($filesBag) ? Arr::flatten($filesBag) : ($filesBag !== null ? [$filesBag] : []);

        foreach ($rawUploadedFiles as $rawFile) {
            if ($rawFile instanceof SymfonyUploadedFile && ! $rawFile->isValid()) {
                if ($rawFile->getError() === UPLOAD_ERR_INI_SIZE || $rawFile->getError() === UPLOAD_ERR_FORM_SIZE) {
                    $limit = ini_get('upload_max_filesize') ?: 'server';

                    return back()->withErrors([
                        'files' => 'Ukuran file "'.$rawFile->getClientOriginalName().'" melebihi batas upload server (upload_max_filesize: '.$limit.'). Silakan kompres foto atau naikkan upload_max_filesize di cPanel.',
                    ]);
                }

                if ($rawFile->getError() === UPLOAD_ERR_PARTIAL) {
                    return back()->withErrors([
                        'files' => 'File "'.$rawFile->getClientOriginalName().'" hanya terunggah sebagian (koneksi terputus). Silakan coba lagi.',
                    ]);
                }
            }
        }

        if ($request->hasFile('file') && ! $request->hasFile('files')) {
            $validated = $request->validate([
                'file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf,mp4', 'extensions:jpg,jpeg,png,webp,pdf,mp4', 'max:20480'],
            ], [
                'file.required' => 'Pilih file bukti terlebih dahulu.',
                'file.mimes' => 'File harus berupa gambar, PDF, atau video MP4.',
                'file.extensions' => 'Ekstensi file harus JPG, JPEG, PNG, WEBP, PDF, atau MP4.',
                'file.max' => 'Ukuran file maksimal 20 MB.',
            ]);
            $files = [$validated['file']];
        } else {
            if ($request->file('files') instanceof SymfonyUploadedFile) {
                $request->files->set('files', [$request->file('files')]);
            }

            $validated = $request->validate([
                'files' => ['required', 'array', 'min:1'],
                'files.*' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf,mp4', 'extensions:jpg,jpeg,png,webp,pdf,mp4', 'max:20480'],
            ], [
                'files.required' => 'Pilih file bukti terlebih dahulu.',
                'files.min' => 'Pilih minimal satu file bukti.',
                'files.*.mimes' => 'Setiap file harus berupa gambar, PDF, atau video MP4.',
                'files.*.extensions' => 'Ekstensi file harus JPG, JPEG, PNG, WEBP, PDF, atau MP4.',
                'files.*.max' => 'Ukuran setiap file maksimal 20 MB.',
            ]);
            $files = $validated['files'];
        }

        $disk = Storage::disk('local');
        $existingFiles = $this->uploadedEvidenceFiles($evidence);

        $maxIndex = 1;
        foreach ($existingFiles as $ef) {
            if (preg_match('/^admin-upload-'.preg_quote($evidence, '/').'-([0-9]+)\./i', $ef, $m)) {
                $maxIndex = max($maxIndex, (int) $m[1]);
            }
        }

        $isFirst = empty($existingFiles);

        foreach ($files as $idx => $file) {
            $extension = strtolower($file->getClientOriginalExtension());
            if ($isFirst && $idx === 0) {
                $filename = 'admin-upload-'.$evidence.'.'.$extension;
                foreach (self::EVIDENCE_UPLOAD_EXTENSIONS as $ext) {
                    $old = 'gtk2026/evidence/admin-upload-'.$evidence.'.'.$ext;
                    if ($disk->exists($old) && $old !== 'gtk2026/evidence/'.$filename) {
                        $disk->delete($old);
                    }
                }
            } else {
                $maxIndex++;
                $filename = 'admin-upload-'.$evidence.'-'.$maxIndex.'.'.$extension;
            }

            $storedPath = $file->storeAs('gtk2026/evidence', $filename, 'local');
            if ($storedPath === false) {
                throw new RuntimeException('Unable to store the uploaded GTK evidence file.');
            }
        }

        $count = count($files);
        $message = $count > 1
            ? "{$count} file bukti \"{$item['title']}\" berhasil diunggah."
            : "Bukti \"{$item['title']}\" berhasil diunggah.";

        return redirect()->route('gtk2026.evidence')
            ->with('status', $message);
    }

    public function deleteEvidence(Request $request, string $evidence): RedirectResponse
    {
        $items = config('gtk2026.evidence', []);
        $item = $items[$evidence] ?? null;
        abort_unless(is_array($item), 404);

        $disk = Storage::disk('local');
        $fileToDelete = $request->input('file');

        if ($fileToDelete) {
            $allowedFiles = $this->uploadedEvidenceFiles($evidence);
            if (in_array($fileToDelete, $allowedFiles, true)) {
                $disk->delete('gtk2026/evidence/'.$fileToDelete);
            }
        }

        return redirect()->route('gtk2026.evidence')
            ->with('status', 'File bukti berhasil dihapus.');
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
            $uploadedFiles = $this->uploadedEvidenceFiles($id);
            $filesList = [];

            if (! empty($uploadedFiles)) {
                foreach ($uploadedFiles as $uFile) {
                    $uPath = $this->resolveEvidenceFile($uFile);
                    if ($uPath !== null) {
                        $filesList[] = [
                            'file' => $uFile,
                            'path' => $uPath,
                            'type' => mime_content_type($uPath),
                            'url' => route($private ? 'gtk2026.evidence.media' : 'gtk2026.media', [
                                'evidence' => $id,
                                'file' => $uFile,
                            ]),
                            'is_uploaded' => true,
                        ];
                    }
                }
            } elseif (! empty($item['file'])) {
                $cPath = $this->resolveEvidenceFile($item['file']);
                if ($cPath !== null) {
                    $filesList[] = [
                        'file' => $item['file'],
                        'path' => $cPath,
                        'type' => mime_content_type($cPath),
                        'url' => route($private ? 'gtk2026.evidence.media' : 'gtk2026.media', $id),
                        'is_uploaded' => false,
                    ];
                }
            }

            $primaryFile = $filesList[0] ?? null;
            $primaryUrl = $primaryFile !== null ? route($private ? 'gtk2026.evidence.media' : 'gtk2026.media', $id) : null;
            if ($primaryFile !== null) {
                $filesList[0]['url'] = $primaryUrl;
            }

            $available = $primaryFile !== null && ($private || ($item['public_approved'] ?? false) === true);
            $videos = $this->youtubeVideos($item, $private);

            $evidence[$id] = [
                'title' => $item['title'], 'category' => $item['category'], 'icon' => $item['icon'],
                'summary' => $item['summary'], 'kind' => $item['kind'] ?? 'media',
                'available' => $available || $videos !== [],
                'url' => $primaryUrl,
                'type' => $primaryFile['type'] ?? null,
                'files' => $filesList,
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

    /**
     * @return array<int, string>
     */
    private function uploadedEvidenceFiles(string $evidence): array
    {
        $disk = Storage::disk('local');
        try {
            $allFiles = $disk->files('gtk2026/evidence');
        } catch (\Throwable) {
            $allFiles = [];
        }

        $matched = [];
        $pattern = '/^admin-upload-'.preg_quote($evidence, '/').'(-[0-9]+)?\.(webp|png|jpe?g|pdf|mp4)$/i';

        foreach ($allFiles as $filePath) {
            $filename = basename($filePath);
            if (preg_match($pattern, $filename)) {
                $matched[] = $filename;
            }
        }

        usort($matched, function (string $a, string $b) use ($evidence): int {
            $isAPrimary = preg_match('/^admin-upload-'.preg_quote($evidence, '/').'\./i', $a);
            $isBPrimary = preg_match('/^admin-upload-'.preg_quote($evidence, '/').'\./i', $b);

            if ($isAPrimary && ! $isBPrimary) {
                return -1;
            }
            if (! $isAPrimary && $isBPrimary) {
                return 1;
            }

            return strnatcasecmp($a, $b);
        });

        return $matched;
    }

    private function uploadedEvidenceFile(string $evidence): ?string
    {
        $files = $this->uploadedEvidenceFiles($evidence);

        return $files[0] ?? null;
    }
}
