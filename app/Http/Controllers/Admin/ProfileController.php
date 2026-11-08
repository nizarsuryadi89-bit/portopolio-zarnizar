<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = Profile::first();

        return view('admin.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'title' => 'nullable|string|max:150',
            'tagline' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'description' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'location' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cv_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $profile = Profile::first();
        if (! $profile) {
            $profile = new Profile;
        }

        $data = $request->except(['photo', 'cv_file']);

        if ($request->hasFile('photo')) {
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }
            $data['photo'] = $request->file('photo')->store('profile', 'public');
        }

        if ($request->hasFile('cv_file')) {
            if ($profile->cv_file) {
                Storage::disk('public')->delete($profile->cv_file);
            }
            $data['cv_file'] = $request->file('cv_file')->store('cv', 'public');
        }

        $profile->fill($data);
        $profile->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
