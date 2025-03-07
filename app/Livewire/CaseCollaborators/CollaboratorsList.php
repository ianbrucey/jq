<?php

namespace App\Livewire\CaseCollaborators;

use App\Models\CaseFile;
use Livewire\Attributes\On;
use Livewire\Component;

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
            $this->dispatch('error', message: __('cases.collaboration.errors.remove_permission'));
            return;
        }

        $collaborator->delete();

        $this->dispatch('collaborator-removed');
        $this->dispatch('notify', message: __('cases.collaboration.notifications.removed'));
    }

    public function render()
    {
        return view('livewire.case-collaborators.collaborators-list', [
            'collaborators' => $this->caseFile->collaborators()->with('user')->get()
        ]);
    }
}
