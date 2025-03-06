<?php

namespace App\Livewire;

use App\Models\CaseCollaborator;
use Livewire\Component;
use App\Models\CaseFile;
use Illuminate\Support\Facades\DB;

class CollaboratorPermissions extends Component
{
    public CaseFile $caseFile;
    public CaseCollaborator $collaborator;
    public string $role;
    public bool $isOpen = false;

    public function mount(CaseCollaborator $collaborator)
    {
        $this->collaborator = $collaborator;
        $this->caseFile = $collaborator->caseFile;
        $this->role = $collaborator->role;
    }

    public function rules()
    {
        return [
            'role' => ['required', 'in:viewer,editor,manager'],
        ];
    }

    public function updatePermissions()
    {
        if (auth()->user()->cannot('manageCollaborators', $this->caseFile)) {
            $this->dispatch('error', message: 'You do not have permission to update collaborator roles.');
            return;
        }

        $this->validate();

        try {
            DB::transaction(function () {
                $this->collaborator->update([
                    'role' => $this->role
                ]);

                $this->collaborator->user->notify(new \App\Notifications\CollaboratorRoleChangedNotification(
                    $this->caseFile,
                    $this->collaborator
                ));
            });

            $this->isOpen = false;
            $this->dispatch('collaborator-updated');
            $this->dispatch('notify', message: 'Permissions updated successfully.');

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Failed to update permissions.');
        }
    }

    public function render()
    {
        return view('livewire.collaborator-permissions');
    }
}