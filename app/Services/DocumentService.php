<?php

namespace App\Services;

use App\Models\Document;
use App\Models\CaseFile;
use App\Jobs\ProcessDocumentJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function store(CaseFile $caseFile, UploadedFile $file, ?string $title = null, ?string $description = null): Document
    {
        $path = $this->storeFile($caseFile, $file);
        
        $document = Document::create([
            'case_file_id' => $caseFile->id,
            'storage_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'title' => $title,
            'description' => $description,
            'ingestion_status' => 'pending'
        ]);

        ProcessDocumentJob::dispatch($document);

        return $document;
    }

    private function storeFile(CaseFile $caseFile, UploadedFile $file): string
    {
        $path = sprintf(
            'cases/%s/documents/%s_%s',
            $caseFile->id,
            time(),
            $file->getClientOriginalName()
        );

        Storage::disk('s3')->put($path, file_get_contents($file));

        return $path;
    }
}