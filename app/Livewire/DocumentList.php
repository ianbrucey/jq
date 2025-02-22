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
    public $search = '';

    protected $queryString = ['search'];

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
        $documents = $this->caseFile->documents()
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('original_filename', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(5);

        return view('livewire.document-list', [
            'documents' => $documents
        ]);
    }
}
