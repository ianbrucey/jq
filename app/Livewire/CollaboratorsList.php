<?php

namespace App\Livewire;

use App\Models\CaseFile;
use Livewire\Component;
use Livewire\Attributes\On;

class CollaboratorsList extends Component
{
    public CaseFile $caseFile;
    
    public function mount(CaseFile $caseFile)
    {
        $this->caseFile = $caseFile;
    }

    #[On('collaborator-added')]
    #[On('collaborator-removed')]
    #[On('collaborator-updated')]
    public function refreshCollaborators()
    {
        $this->caseFile->refresh();
    }

    public function removeCollaborator($collaboratorId)
    {
        $collaborator = $this->caseFile->collaborators()->findOrFail($collaboratorId);
        
        if (auth()->user()->cannot('removeCollaborators', $this->caseFile)) {
            $this->dispatch('error', message: 'You do not have permission to remove collaborators.');
            return;
        }

        $collaborator->delete();
        
        $this->dispatch('collaborator-removed');
        $this->dispatch('notify', message: 'Collaborator removed successfully.');
    }

    public function render()
    {
        return view('livewire.collaborators-list', [
            'collaborators' => $this->caseFile->collaborators()->with('user')->get()
        ]);
    }
}