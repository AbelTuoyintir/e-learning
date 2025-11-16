<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    // public function index()
    // {
    //     $modules = Module::with('course')->latest()->get();
    //     return view('modules.index', compact('modules'));
    // }

    public function create()
    {
        return view('modules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'duration_minutes' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        Module::create($validated);

        return redirect()->route('courses.index')->with('success', 'Module created successfully.');
    }

    public function show(Module $module)
    {
        $module->load('topics');
        return view('modules.show', compact('module'));
    }

    public function edit(Module $module)
    {
        return view('modules.edit', compact('module'));
    }

    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'duration_minutes' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $module->update($validated);

        return redirect()->route('courses.show')->with('success', 'Module updated successfully.');
    }

    public function destroy(Module $module)
    {
        $module->delete();
        return redirect()->route('modules.index')->with('success', 'Module deleted successfully.');
    }
}
