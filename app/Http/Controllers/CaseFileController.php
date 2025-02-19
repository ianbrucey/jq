<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CaseFileController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', CaseFile::class);
        $caseFiles = auth()->user()->caseFiles()->latest()->paginate(5); // Change 10 to your desired number of items per page
        return view('case-files.index', compact('caseFiles'));
    }

    public function create()
    {
        $this->authorize('create', CaseFile::class);
        return view('case-files.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', CaseFile::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'desired_outcome' => 'required|string'
        ]);

        $caseFile = $request->user()->caseFiles()->create($validated);

        return redirect()->route('case-files.show', $caseFile);
    }

    public function show(CaseFile $caseFile)
    {
        $this->authorize('view', $caseFile);
        return view('case-files.show', compact('caseFile'));
    }

    public function edit(CaseFile $caseFile)
    {
        $this->authorize('update', $caseFile);
        return view('case-files.edit', compact('caseFile'));
    }

    public function update(Request $request, CaseFile $caseFile)
    {
        $this->authorize('update', $caseFile);
        // ... update logic
    }

    public function destroy(CaseFile $caseFile)
    {
        $this->authorize('delete', $caseFile);
        $caseFile->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Case file deleted successfully']);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Case file deleted successfully.');
    }
}
