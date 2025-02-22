<?php

namespace App\Livewire;

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

    protected $listeners = ['removeFile'];

    public function mount($caseFile = null)
    {
        $this->caseFile = $caseFile;
    }

//    public function updated() {
//        dd($this->files);
//    }

    public function updatedFiles()
    {
        foreach ($this->files as $file) {
            $index = count($this->queuedFiles);
            $this->queuedFiles[$index] = $file;
            $this->documentTitles[$index] = '';
            $this->documentDescriptions[$index] = '';
        }

        $this->files = []; // Clear the temporary upload array
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
        try {
            $file = $this->queuedFiles[$key];
            $title = $this->documentTitles[$key] ?? null;
            $description = $this->documentDescriptions[$key] ?? null;

            app(DocumentService::class)->store(
                $this->caseFile,
                $file,
                $title,
                $description
            );

            $this->removeFile($key);
            $this->dispatch('document-uploaded');
        } catch (\Exception $e) {
            Log::error('Failed to upload document: ' . $e->getMessage());
            $this->addError('upload', 'Failed to upload document: ' . $e->getMessage());
        }
    }

    public function saveAllDocuments()
    {

        foreach (array_keys($this->queuedFiles) as $index => $file) {
            $this->saveDocument($index);
        }
    }

    public function render()
    {
        return view('livewire.document-uploader');
    }
}
