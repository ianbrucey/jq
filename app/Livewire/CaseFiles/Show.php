<?php

namespace App\Livewire\CaseFiles;

use App\Models\CaseFile;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Show extends Component
{
    use AuthorizesRequests;

    public CaseFile $caseFile;

    public function mount(CaseFile $caseFile)
    {
        $this->caseFile = $caseFile;
    }

    public function enableCollaboration()
    {
        $this->authorize('update', $this->caseFile);

        $this->caseFile->update([
            'collaboration_enabled' => true,
            'max_collaborators' => 5 // Default value
        ]);

        $this->dispatch('notify', [
            'message' => __('collaboration.notifications.enabled'),
            'type' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.case-files.show');
    }
}