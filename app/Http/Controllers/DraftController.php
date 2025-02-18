<?php

namespace App\Http\Controllers;

use App\Models\Draft;
use App\Models\CaseFile;
use Illuminate\Http\Request;

class DraftController extends Controller
{
    public function create(CaseFile $caseFile)
    {
        return view('drafts.create', compact('caseFile'));
    }

    public function store(Request $request, CaseFile $caseFile)
    {
        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        $caseFile->drafts()->create($validated);

        return redirect()->route('case-files.show', $caseFile);
    }
}
