<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentUploader extends Component
{
    use WithFileUploads;

    public $files = [];
    public $documentTitles = [];
    public $documentDescriptions = [];

    protected $listeners = ['removeFile'];

    public function removeFile($index)
    {
        unset($this->files[$index]);
        unset($this->documentTitles[$index]);
        unset($this->documentDescriptions[$index]);
        $this->files = array_values($this->files);
        $this->documentTitles = array_values($this->documentTitles);
        $this->documentDescriptions = array_values($this->documentDescriptions);
    }

    public function saveDocument($index)
    {
        // Add your save logic here
        // You can access:
        // - $this->files[$index] for the file
        // - $this->documentTitles[$index] for the title
        // - $this->documentDescriptions[$index] for the description

        // After saving, you might want to show a success message
    }

    public function render()
    {
        return view('livewire.document-uploader');
    }
}
