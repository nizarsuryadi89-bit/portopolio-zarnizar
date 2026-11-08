<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::orderBy('sort_order')->orderBy('created_at', 'desc')->get();

        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create()
    {
        return view('admin.portfolios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'url' => 'nullable|url',
            'date' => 'nullable|date',
            'is_active' => 'nullable',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['title']).'-'.uniqid();
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('portfolios', 'public');
        }

        Portfolio::create($validated);

        return redirect()->route('admin.portfolios.index')->with('success', 'Portfolio created successfully.');
    }

    public function edit(Portfolio $portfolio)
    {
        return view('admin.portfolios.edit', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'url' => 'nullable|url',
            'date' => 'nullable|date',
            'is_active' => 'nullable',
            'sort_order' => 'integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($portfolio->image) {
                Storage::disk('public')->delete($portfolio->image);
            }
            $validated['image'] = $request->file('image')->store('portfolios', 'public');
        }

        $portfolio->update($validated);

        return redirect()->route('admin.portfolios.index')->with('success', 'Portfolio updated successfully.');
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->image) {
            Storage::disk('public')->delete($portfolio->image);
        }
        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')->with('success', 'Portfolio deleted successfully.');
    }
}
