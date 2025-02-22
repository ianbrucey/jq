<?php

namespace App\Services;

use App\Models\Document;
use App\Models\CaseFile;
use App\Jobs\ProcessDocumentJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;

class DocumentService
{
    public function store(CaseFile $caseFile, UploadedFile $file, ?string $title = null, ?string $description = null): Document
    {
        if (is_string($file)) {
            // Handle file path string
            $path = $file;
            $filename = basename($path);
            $fileSize = filesize($path);
            $mimeType = mime_content_type($path);
        } else {
            // Handle UploadedFile instance
            $path = $this->storeFile($caseFile, $file);
            $filename = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();
        }

        $document = Document::create([
            'case_file_id' => $caseFile->id,
            'storage_path' => $path,
            'original_filename' => $filename,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'title' => $title,
            'description' => $description,
            'ingestion_status' => 'pending'
        ]);

        ProcessDocumentJob::dispatch($document);

        return $document;
    }

    private function storeFile(CaseFile $caseFile,  $file): string
    {
        $path = sprintf(
            'cases/%s/documents/%s',
            $caseFile->id,
            time()
        );

        // returns the path where the file was stored. EX: cases/1/documents/1687454239.pdf
        return $file->storeAs($path, $file->getClientOriginalName(),'s3');
    }
}
