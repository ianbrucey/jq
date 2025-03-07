<?php

namespace App\Livewire\Docket;

use App\Models\DocketEntry;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EntryDetails extends Component
{
    public DocketEntry $entry;

    public function getRelatedDocumentsProperty()
    {
        return $this->entry->documents()
            ->with('exhibits')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getRelatedCommunicationsProperty()
    {
        return $this->entry->communications()
            ->with('participants.party')
            ->orderBy('sent_at', 'desc')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.docket.entry-details', [
            'relatedDocuments' => $this->relatedDocuments,
            'relatedCommunications' => $this->relatedCommunications,
        ]);
    }
}