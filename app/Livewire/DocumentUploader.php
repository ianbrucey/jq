<?php

namespace App\Livewire;

use App\Models\CaseFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Log;

class DocumentUploader extends Component
{
    use WithFileUploads;

    public $files = [];
    public $queuedFiles = [];
    public $documentTitles = [];
    public $documentDescriptions = [];
    public $caseFile;
    public $isSavingAll = false;
    public $savingDocuments = [];

    protected $listeners = ['removeFile'];

    public function mount($caseFile)
    {
        $this->caseFile = !is_object($caseFile) ? CaseFile::find(intval($caseFile)) : $caseFile;

//        dd($this->caseFile);

        if(empty($this->caseFile)) {
            throw new \Exception('Cannot upload documents without a case file');
        }
    }

//    public function updated() {
//        dd($this->files);
//    }

    public function updatedFiles()
    {
        foreach ($this->files as $file) {
            $originalName = $file->getClientOriginalName();
            $sanitizedName = str_replace(' ', '_', $originalName);

            // Store file and create object structure
            $storedPath = $file->storeAs('temp', $sanitizedName);

            $fileObject = [
                'file' => $file,
                'metadata' => [
                    'name' => $originalName,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                    'progress' => 100,
                    'temporaryUrl' => null
                ]
            ];

            $index = count($this->queuedFiles);
            $this->queuedFiles[$index] = $fileObject;
            $this->documentTitles[$index] = ''; // Initialize with empty string
            $this->documentDescriptions[$index] = '';
        }

        $this->files = [];
    }

    public function removeFile($key)
    {
        unset($this->queuedFiles[$key]);
        unset($this->documentTitles[$key]);
        unset($this->documentDescriptions[$key]);

        // Re-index arrays
        $this->queuedFiles = array_values($this->queuedFiles);
        $this->documentTitles = array_values($this->documentTitles);
        $this->documentDescriptions = array_values($this->documentDescriptions);
    }

    public function saveDocument($key)
    {
        $this->savingDocuments[$key] = true;

        try {
            $fileObject = $this->queuedFiles[$key];
            $title = $this->documentTitles[$key] ?? null;
            $description = $this->documentDescriptions[$key] ?? null;

            app(DocumentService::class)->store(
                $this->caseFile,
                $fileObject['file'],
                $title,
                $description
            );

            $this->removeFile($key);
            $this->dispatch('document-uploaded');
        } catch (\Exception $e) {
            Log::error('Failed to upload document: ' . $e->getMessage());
            $this->addError('upload', 'Failed to upload document: ' . $e->getMessage());
        } finally {
            unset($this->savingDocuments[$key]);
        }
    }

    public function saveAllDocuments()
    {
        $this->isSavingAll = true;

        try {
            while (!empty($this->queuedFiles)) {
                $this->saveDocument(array_key_first($this->queuedFiles));
            }
        } finally {
            $this->isSavingAll = false;
        }
    }

    public function render()
    {
        return view('livewire.document-uploader');
    }
}
