<?php

namespace App\Livewire\Correspondence;

use App\Models\Party;
use App\Models\Thread;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class AddCommunicationForm extends Component
{
    use WithFileUploads;

    public $showUploadModal = false;

    public Thread $thread;
    public $type = 'email';
    public $content;
    public $subject;
    public $sent_at;
    public $selectedParties = []; // Format: ['party_id' => 'role']
    public $documents = [];
    public $partySearch = '';
    public $searchResults = [];
    public $documentSearch = '';
    public $documentSearchResults = [];
    public $selectedDocuments = [];
    public $newDocuments = [];

    protected $rules = [
        'type' => 'required|in:email,letter,phone,other',
        'content' => 'required_without:selectedDocuments|string|nullable',
        'subject' => 'nullable|string|max:255',
        'sent_at' => 'required|date',
        'selectedParties' => 'required|array|min:1',
        'selectedParties.*' => 'in:sender,recipient',
        'selectedDocuments' => 'array',
        'newDocuments.*' => 'nullable|file|max:10240'
    ];

    public function mount()
    {
        $this->sent_at = now()->format('Y-m-d H:i');
    }

    public function updatedPartySearch()
    {
        if (strlen($this->partySearch) >= 2) {
            $this->searchResults = Party::where('name', 'like', "%{$this->partySearch}%")
                ->orWhere('email', 'like', "%{$this->partySearch}%")
                ->limit(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function clearPartySearch()
    {
        $this->partySearch = '';
        $this->searchResults = [];
    }

    public function addParticipant($partyId, $role)
    {
        $this->selectedParties[$partyId] = $role;
        $this->partySearch = ''; // Reset the search input
        $this->searchResults = []; // Clear the search results
    }

    public function removeParticipant($partyId)
    {
        unset($this->selectedParties[$partyId]);
    }

    public function updatedDocumentSearch()
    {
        if (strlen($this->documentSearch) >= 2) {
            // Search both original_filename and title fields
            $this->documentSearchResults = $this->thread->caseFile
                ->documents()
                ->where(function ($query) {
                    $query->where('original_filename', 'like', "%{$this->documentSearch}%")
                          ->orWhere('title', 'like', "%{$this->documentSearch}%");
                })
                ->limit(5)
                ->get();
        } else {
            $this->documentSearchResults = [];
        }
    }

    public function clearDocumentSearch()
    {
        $this->documentSearch = '';
        $this->documentSearchResults = [];
    }

    public function addDocument($documentId)
    {
        if (!in_array($documentId, $this->selectedDocuments)) {
            $this->selectedDocuments[] = $documentId;
        }
        $this->documentSearch = '';
        $this->documentSearchResults = [];
    }

    public function removeDocument($documentId)
    {
        $this->selectedDocuments = array_values(array_diff($this->selectedDocuments, [$documentId]));
    }

    public function removeNewDocument($index)
    {
        $newDocuments = $this->newDocuments;
        unset($newDocuments[$index]);
        $this->newDocuments = array_values($newDocuments); // Reset array keys
    }

    public function save()
    {
        $this->validate();

        $communication = $this->thread->communications()->create([
            'type' => $this->type,
            'content' => $this->content,
            'subject' => $this->subject,
            'sent_at' => $this->sent_at,
        ]);

        // Handle participants
        foreach ($this->selectedParties as $partyId => $role) {
            $communication->participants()->attach($partyId, ['role' => $role]);
        }

        // Handle existing documents
        if (!empty($this->selectedDocuments)) {
            $communication->documents()->attach($this->selectedDocuments);
        }

        // Handle new document uploads
        if (!empty($this->newDocuments)) {
            foreach ($this->newDocuments as $document) {
                $newDoc = $this->thread->caseFile->documents()->create([
                    'name' => $document->getClientOriginalName(),
                    'file_path' => $document->store('documents', 'public'),
                    'file_size' => $document->getSize(),
                    'mime_type' => $document->getMimeType(),
                ]);

                $communication->documents()->attach($newDoc->id);
            }
        }

        $this->reset([
            'type',
            'content',
            'subject',
            'selectedParties',
            'selectedDocuments',
            'newDocuments',
            'partySearch',
            'searchResults'
        ]);

        $this->dispatch('communication-saved');
        $this->dispatch('close-modal');
    }

    public function cancel()
    {
        $this->reset([
            'type',
            'content',
            'subject',
            'selectedParties',
            'selectedDocuments',
            'newDocuments',
            'partySearch',
            'searchResults'
        ]);
        $this->dispatch('close-modal');
    }

    #[On('document-uploaded')]
    public function handleDocumentUploaded($document)
    {
        if(isset($document['id'])){
            $this->selectedDocuments[] = $document['id'];
        }

        $this->showUploadModal = false;
    }

    public function render()
    {
        return view('livewire.correspondence.add-communication-form');
    }
}
