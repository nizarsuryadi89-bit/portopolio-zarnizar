<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::orderBy('sort_order')->orderBy('start_date', 'desc')->get();

        return view('admin.experiences.index', compact('experiences'));
    }

    public function create()
    {
        return view('admin.experiences.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'position' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'sort_order' => 'required|integer',
        ]);

        $data = $request->all();
        $data['is_current'] = $request->has('is_current');
        if ($data['is_current']) {
            $data['end_date'] = null;
        }

        Experience::create($data);

        return redirect()->route('admin.experiences.index')->with('success', 'Experience created successfully.');
    }

    public function edit(Experience $experience)
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    public function update(Request $request, Experience $experience)
    {
        $request->validate([
            'position' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'sort_order' => 'required|integer',
        ]);

        $data = $request->all();
        $data['is_current'] = $request->has('is_current');
        if ($data['is_current']) {
            $data['end_date'] = null;
        }

        $experience->update($data);

        return redirect()->route('admin.experiences.index')->with('success', 'Experience updated successfully.');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();

        return redirect()->route('admin.experiences.index')->with('success', 'Experience deleted successfully.');
    }
}
