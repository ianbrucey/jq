<?php

namespace App\Http\Controllers;

use App\Models\OpenAiProject;
use Illuminate\Http\Request;

class OpenAiProjectController extends Controller
{
    public function index()
    {
        return view('openai.projects.index', [
            'projects' => OpenAiProject::all()
        ]);
    }

    public function create()
    {
        return view('openai.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'api_key' => 'required|string',
            'organization_id' => 'nullable|string',
        ]);

        OpenAiProject::create($validated);

        return redirect()->route('openai.projects.index')
            ->with('success', 'OpenAI project created successfully');
    }

    public function edit(OpenAiProject $project)
    {
        return view('openai.projects.edit', compact('project'));
    }

    public function update(Request $request, OpenAiProject $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'api_key' => 'nullable|string',
            'organization_id' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Only update API key if a new one is provided
        if (empty($validated['api_key'])) {
            unset($validated['api_key']);
        }

        $validated['is_active'] = $request->has('is_active');

        $project->update($validated);

        return redirect()->route('openai.projects.index')
            ->with('success', 'OpenAI project updated successfully');
    }

    public function destroy(OpenAiProject $project)
    {
        $project->delete();

        return redirect()->route('openai.projects.index')
            ->with('success', 'OpenAI project deleted successfully');
    }
}
