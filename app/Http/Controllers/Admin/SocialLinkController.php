<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function index()
    {
        $socialLinks = SocialLink::orderBy('sort_order')->get();

        return view('admin.social_links.index', compact('socialLinks'));
    }

    public function create()
    {
        return view('admin.social_links.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'url' => 'required|url|max:255',
            'sort_order' => 'required|integer',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        SocialLink::create($data);

        return redirect()->route('admin.social-links.index')->with('success', 'Social Media Link created successfully.');
    }

    public function edit(SocialLink $socialLink)
    {
        return view('admin.social_links.edit', compact('socialLink'));
    }

    public function update(Request $request, SocialLink $socialLink)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'url' => 'required|url|max:255',
            'sort_order' => 'required|integer',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $socialLink->update($data);

        return redirect()->route('admin.social-links.index')->with('success', 'Social Media Link updated successfully.');
    }

    public function destroy(SocialLink $socialLink)
    {
        $socialLink->delete();

        return redirect()->route('admin.social-links.index')->with('success', 'Social Media Link deleted successfully.');
    }
}
