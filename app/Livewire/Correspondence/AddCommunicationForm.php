<?php

namespace App\Livewire\Correspondence;

use App\Models\Thread;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddCommunicationForm extends Component
{
    use WithFileUploads;

    public Thread $thread;
    public $type = 'email';
    public $content;
    public $subject;
    public $sent_at;
    public $selectedParties = [];
    public $documents = [];

    protected $rules = [
        'type' => 'required|in:email,letter,phone,other',
        'content' => 'required_without:documents|string|nullable',
        'subject' => 'nullable|string|max:255',
        'sent_at' => 'required|date',
        'selectedParties' => 'required|array|min:1',
        'documents.*' => 'nullable|file|max:10240'
    ];

    public function mount()
    {
        $this->sent_at = now()->format('Y-m-d H:i');
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

        // Handle documents
        foreach ($this->documents as $document) {
            // Implement document handling logic here
        }

        $this->reset(['type', 'content', 'subject', 'selectedParties', 'documents']);
        $this->emit('communicationAdded');
    }

    public function render()
    {
        return view('livewire.correspondence.add-communication-form');
    }
}