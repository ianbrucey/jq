<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CaseFile;
use App\Models\Document;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class DocumentList extends Component
{
    use WithPagination;

    public CaseFile $caseFile;
    public $showingPreviewModal = false;
    public $previewDocument = null;
    public $documentUrls = [];

    protected $listeners = [
        'document-uploaded' => '$refresh'
    ];

    public function mount(CaseFile $caseFile)
    {
        $this->caseFile = $caseFile;
    }

    public function getDocumentUrl($documentId)
    {
        if (!isset($this->documentUrls[$documentId])) {
            $document = Document::find($documentId);
            $this->documentUrls[$documentId] = Storage::disk('s3')->temporaryUrl(
                $document->storage_path,
                now()->addMinutes(5)
            );
        }

        return $this->documentUrls[$documentId];
    }

    public function preview($documentId)
    {
        $this->previewDocument = Document::find($documentId);
        $this->getDocumentUrl($documentId); // Generate URL only when previewing
        $this->showingPreviewModal = true;
    }

    public function closePreviewModal()
    {
        $this->showingPreviewModal = false;
        $this->previewDocument = null;
        $this->documentUrls = []; // Clear cached URLs
    }

    public function render()
    {
        return view('livewire.document-list', [
            'documents' => $this->caseFile->documents()
                ->latest()
                ->paginate(10)
        ]);
    }
}
