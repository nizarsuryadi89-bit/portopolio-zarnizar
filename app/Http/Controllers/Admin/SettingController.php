<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_title' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:1000'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:500'],
            'sales_home_title' => ['nullable', 'string', 'max:255'],
            'sales_home_description' => ['nullable', 'string', 'max:1000'],
            'sales_home_headline' => ['nullable', 'string', 'max:255'],
            'sales_home_intro' => ['nullable', 'string', 'max:1000'],
            'ga4_measurement_id' => ['nullable', 'string', 'regex:/^G-[A-Z0-9]{4,20}$/'],
            'google_site_verification' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z0-9_-]+$/'],
            'favicon' => ['nullable', 'image', 'max:2048'],
            'kulapos_screenshot' => ['nullable', 'image', 'max:4096'],
            'kulapos_screenshot_secondary' => ['nullable', 'image', 'max:4096'],
        ], [
            'ga4_measurement_id.regex' => 'Gunakan ID pengukuran GA4 yang valid, diawali G-.',
            'google_site_verification.regex' => 'Masukkan hanya token verifikasi Google, bukan tag HTML.',
        ]);

        foreach (['favicon', 'kulapos_screenshot', 'kulapos_screenshot_secondary'] as $imageKey) {
            unset($data[$imageKey]);

            if ($request->hasFile($imageKey)) {
                $data[$imageKey] = $request->file($imageKey)->store('settings', 'public');
            }
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
