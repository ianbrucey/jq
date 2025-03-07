<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use Illuminate\Http\Request;

class DocketController extends Controller
{
    public function index(CaseFile $caseFile)
    {
        return view('case-files.docket', [
            'caseFile' => $caseFile
        ]);
    }
}