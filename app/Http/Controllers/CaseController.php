<?php

namespace App\Http\Controllers;

use App\Models\LegalCase;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    public function create()
    {
        return view('cases.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'desired_outcome' => 'required|string'
        ]);

        $case = $request->user()->cases()->create($validated);

        return redirect()->route('cases.show', $case);
    }

    public function show(LegalCase $case)
    {
        return view('cases.show', compact('case'));
    }
}