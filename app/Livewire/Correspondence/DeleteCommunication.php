<?php

namespace App\Livewire\Correspondence;

use App\Models\Communication;
use Livewire\Component;

class DeleteCommunication extends Component
{
    public Communication $communication;
    public $showDeleteModal = false;

    public function mount(Communication $communication)
    {
        $this->communication = $communication;
    }

    public function delete()
    {
        $this->communication->delete();

        // Dispatch an event to refresh the thread view
        $this->dispatch('communicationDeleted')->to('correspondence.thread-view');

        $this->showDeleteModal = false;
    }

    public function render()
    {
        return view('livewire.correspondence.delete-communication');
    }
}
