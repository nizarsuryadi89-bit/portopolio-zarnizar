<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index()
    {
        $educations = Education::orderBy('sort_order')->orderBy('start_year', 'desc')->get();

        return view('admin.educations.index', compact('educations'));
    }

    public function create()
    {
        return view('admin.educations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_year' => 'required|string|max:10',
            'end_year' => 'nullable|string|max:10',
            'sort_order' => 'required|integer',
        ]);

        Education::create($request->all());

        return redirect()->route('admin.educations.index')->with('success', 'Education created successfully.');
    }

    public function edit(Education $education)
    {
        return view('admin.educations.edit', compact('education'));
    }

    public function update(Request $request, Education $education)
    {
        $request->validate([
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_year' => 'required|string|max:10',
            'end_year' => 'nullable|string|max:10',
            'sort_order' => 'required|integer',
        ]);

        $education->update($request->all());

        return redirect()->route('admin.educations.index')->with('success', 'Education updated successfully.');
    }

    public function destroy(Education $education)
    {
        $education->delete();

        return redirect()->route('admin.educations.index')->with('success', 'Education deleted successfully.');
    }
}
