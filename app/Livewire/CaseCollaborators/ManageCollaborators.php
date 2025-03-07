<?php

namespace App\Livewire\CaseCollaborators;

use App\Models\CaseFile;
use App\Models\User;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ManageCollaborators extends Component
{
    use AuthorizesRequests;

    public CaseFile $caseFile;
    public string $email = '';
    public string $role = 'viewer';

    protected $rules = [
        'email' => 'required|email|exists:users,email',
        'role' => 'required|in:viewer,editor,manager'
    ];

    public function mount(CaseFile $caseFile)
    {
        $this->caseFile = $caseFile;
        $this->authorize('manageCollaborators', $caseFile);
    }

    public function invite()
    {
        $this->authorize('inviteCollaborators', $this->caseFile);

        $this->validate();

        $user = User::where('email', $this->email)->first();

        if ($this->caseFile->hasCollaborator($user)) {
            $this->addError('email', __('collaboration.errors.already_collaborator'));
            return;
        }

        $this->caseFile->collaborators()->create([
            'user_id' => $user->id,
            'role' => $this->role,
            'status' => 'pending'
        ]);

        $this->dispatch('collaborator-invited');
        $this->reset('email', 'role');
    }

    public function removeCollaborator($collaboratorId)
    {
        $this->authorize('removeCollaborators', $this->caseFile);

        $collaborator = $this->caseFile->collaborators()->findOrFail($collaboratorId);
        $user = $collaborator->user;

        $collaborator->delete();

        event(new \App\Events\CollaboratorRemoved($this->caseFile, $user));

        $this->dispatch('collaborator-removed');
        $this->dispatch('notify', message: __('collaboration.notifications.removed'));
    }

    public function render()
    {
        return view('livewire.case-collaborators.manage-collaborators', [
            'collaborators' => $this->caseFile->collaborators()->with('user')->get()
        ]);
    }
}
