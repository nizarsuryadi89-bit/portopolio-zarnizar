<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::orderBy('date', 'desc')->get();

        return view('admin.activities.index', compact('activities'));
    }

    public function create()
    {
        return view('admin.activities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'date' => 'nullable|date',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        Activity::create($validated);

        return redirect()->route('admin.activities.index')->with('success', 'Activity created successfully.');
    }

    public function edit(Activity $activity)
    {
        return view('admin.activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'date' => 'nullable|date',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        $activity->update($validated);

        return redirect()->route('admin.activities.index')->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity)
    {
        if ($activity->image) {
            Storage::disk('public')->delete($activity->image);
        }
        $activity->delete();

        return redirect()->route('admin.activities.index')->with('success', 'Activity deleted successfully.');
    }
}
