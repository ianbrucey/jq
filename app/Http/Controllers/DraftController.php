<?php

namespace App\Http\Controllers;

use App\Models\Draft;
use App\Models\LegalCase;
use Illuminate\Http\Request;

class DraftController extends Controller
{
    public function create(LegalCase $case)
    {
        return view('drafts.create', compact('case'));
    }

    public function store(Request $request, LegalCase $case)
    {
        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        $case->drafts()->create($validated);

        return redirect()->route('cases.show', $case);
    }
}