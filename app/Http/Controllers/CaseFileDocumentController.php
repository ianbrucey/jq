<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use Illuminate\Http\Request;

class CaseFileDocumentController extends Controller
{
    public function index(CaseFile $caseFile)
    {
        return view('case-files.documents.index', [
            'caseFile' => $caseFile
        ]);
    }
}