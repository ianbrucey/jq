<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use App\Models\Thread;
use Illuminate\Http\Request;

class CorrespondenceController extends Controller
{
    public function show(CaseFile $caseFile, Thread $thread)
    {
        if ($thread->case_file_id !== $caseFile->id) {
            abort(404);
        }

        return view('case-files.correspondences.show', [
            'caseFile' => $caseFile,
            'thread' => $thread
        ]);
    }
}
