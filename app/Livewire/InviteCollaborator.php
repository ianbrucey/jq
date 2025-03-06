<?php

namespace App\Livewire;

use App\Models\CaseFile;
use App\Models\CaseCollaborator;
use App\Notifications\CollaborationInviteNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\User;

class InviteCollaborator extends Component
{
    public CaseFile $caseFile;
    public string $email = '';
    public string $role = 'viewer';
    public bool $isOpen = false;

    public function rules()
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'role' => ['required', 'in:viewer,editor,manager'],
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'This email is not registered in our system.',
        ];
    }

    public function invite()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        // Check if already a collaborator
        if ($this->caseFile->collaborators()->where('user_id', $user->id)->exists()) {
            $this->addError('email', 'This user is already a collaborator.');
            return;
        }

        try {
            DB::transaction(function () use ($user) {
                $collaborator = $this->caseFile->collaborators()->create([
                    'user_id' => $user->id,
                    'role' => $this->role,
                    'status' => 'pending'
                ]);

                $user->notify(new CollaborationInviteNotification($this->caseFile, $collaborator));
            });

            $this->reset('email', 'role', 'isOpen');
            $this->dispatch('collaborator-added');
            $this->dispatch('notify', message: 'Invitation sent successfully.');
            
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Failed to send invitation.');
        }
    }

    public function render()
    {
        return view('livewire.invite-collaborator');
    }
}