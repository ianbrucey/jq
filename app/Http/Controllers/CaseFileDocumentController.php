<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CaseFileDocumentController extends Controller
{
    public function __construct(
        private DocumentService $documentService
    ) {}

    public function index(CaseFile $caseFile)
    {
        return view('case-files.documents.index', [
            'caseFile' => $caseFile
        ]);
    }

    public function store(Request $request, CaseFile $caseFile)
    {
        $request->validate([
            'document' => 'required|file|max:10240',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string'
        ]);

        try {
            $document = $this->documentService->store(
                $caseFile,
                $request->file('document'),
                $request->input('title'),
                $request->input('description')
            );

            return response()->json([
                'message' => 'Document uploaded successfully',
                'document' => $document
            ], 201);
        } catch (\Exception $e) {
            Log::error('Document upload failed', [
                'case_id' => $caseFile->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Failed to upload document'
            ], 500);
        }
    }
}
