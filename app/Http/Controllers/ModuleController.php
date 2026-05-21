<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
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

        $module = Module::create($validated);

        if ($request->wantsJson() || $request->isJson() || true) { // Always return JSON for fetch calls
            return response()->json(['success' => true, 'module' => $module]);
        }

        return redirect()->route('courses.index')->with('success', 'Module created successfully.');
    }

    public function show(Module $module)
    {
        $module->load(['topics', 'course']);
        return view('course.module', compact('module'));
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

        if ($request->wantsJson() || $request->isJson() || true) {
            return response()->json(['success' => true, 'module' => $module]);
        }

        return redirect()->route('courses.show')->with('success', 'Module updated successfully.');
    }

    public function destroy(Request $request, Module $module)
    {
        $module->delete();
        
        if ($request->wantsJson() || $request->isJson() || true) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->route('modules.index')->with('success', 'Module deleted successfully.');
    }

    public function updateStatus(Request $request, Module $module)
    {
        $request->validate(['is_active' => 'required|boolean']);
        $module->update(['is_active' => $request->is_active]);
        return response()->json(['success' => true]);
    }

    public function bulkStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:modules,id',
            'is_active' => 'required|boolean'
        ]);
        
        Module::whereIn('id', $request->ids)->update(['is_active' => $request->is_active]);
        return response()->json(['success' => true]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:modules,id'
        ]);
        
        $deleted = Module::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'deleted' => $deleted]);
    }
}
