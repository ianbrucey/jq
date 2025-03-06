<?php

namespace App\Livewire\Correspondence;

use App\Models\Thread;
use App\Models\Document;
use App\Models\Party;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class ThreadView extends Component
{
    use WithPagination;

    public Thread $thread;
    public $showAddCommunicationModal = false;
    public $showingPreviewModal = false;
    public $previewDocument = null;
    public $documentUrls = [];
    public $showingPartyModal = false;
    public $selectedParty = null;

    #[Url(history: true)]
    public $search = '';

    #[On('communication-added')]
    public function handleCommunicationAdded()
    {
        $this->showAddCommunicationModal = false;
        $this->resetPage();
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function boot()
    {
        $this->search = trim($this->search);
    }

    public function updating($name, $value)
    {
        if ($name === 'search') {
            $this->resetPage();
        }
    }

    public function clearSearch()
    {
        $this->reset('search');
        $this->resetPage();
    }

    #[On('close-add-communication-modal')]
    public function closeAddCommunicationModal()
    {
        $this->showAddCommunicationModal = false;
    }

    #[On('communicationAdded')]
    public function refreshThread()
    {
        $this->thread->refresh();
        $this->showAddCommunicationModal = false;
    }

    public function mount(Thread $thread)
    {
        $this->thread = $thread->load(['communications' => function($query) {
            $query->with(['participants', 'documents'])
                  ->orderBy('sent_at', 'asc');
        }]);
    }

    public function render()
    {
        return view('livewire.correspondence.thread-view', [
            'communications' => $this->getCommunications()
        ]);
    }

    protected function getCommunications()
    {
        return $this->thread->communications()
            ->with(['participants', 'documents'])
            ->when($this->search, function ($query) {
                $search = trim($this->search);
                $query->where(function ($q) use ($search) {
                    $q->where('subject', 'like', '%' . $search . '%')
                      ->orWhere('content', 'like', '%' . $search . '%')
                      ->orWhereHas('participants', function ($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      });
                });
            })
            ->latest('sent_at')
            ->paginate(5);
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

    public function showPartyModal($partyId)
    {
        $this->selectedParty = Party::find($partyId);
        $this->showingPartyModal = true;
    }

    public function closePartyModal()
    {
        $this->showingPartyModal = false;
        $this->selectedParty = null;
    }
}
