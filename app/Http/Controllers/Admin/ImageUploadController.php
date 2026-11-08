<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');

            // Validasi file (opsional tapi disarankan)
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // maks 5MB
            ]);

            // Simpan gambar ke storage/app/public/articles
            $path = $file->store('articles', 'public');

            // Dapatkan URL yang bisa diakses publik
            $url = Storage::url($path);

            return response()->json([
                'url' => $url,
            ]);
        }

        return response()->json([
            'error' => [
                'message' => 'Gagal mengupload gambar.',
            ],
        ], 400);
    }
}
