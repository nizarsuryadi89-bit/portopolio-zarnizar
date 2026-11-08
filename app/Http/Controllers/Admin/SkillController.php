<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::orderBy('sort_order')->get();

        return view('admin.skills.index', compact('skills'));
    }

    public function create()
    {
        return view('admin.skills.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'percentage' => 'nullable|integer|min:0|max:100',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        Skill::create($data);

        return redirect()->route('admin.skills.index')->with('success', 'Skill created successfully.');
    }

    public function edit(Skill $skill)
    {
        return view('admin.skills.edit', compact('skill'));
    }

    public function update(Request $request, Skill $skill)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'percentage' => 'nullable|integer|min:0|max:100',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $skill->update($data);

        return redirect()->route('admin.skills.index')->with('success', 'Skill updated successfully.');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return redirect()->route('admin.skills.index')->with('success', 'Skill deleted successfully.');
    }
}
