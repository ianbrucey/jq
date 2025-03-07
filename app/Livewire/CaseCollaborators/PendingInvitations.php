<?php

namespace App\Livewire\CaseCollaborators;

use Livewire\Component;
use App\Models\CaseCollaborator;

class PendingInvitations extends Component
{
    public function acceptInvitation($collaboratorId)
    {
        $collaborator = CaseCollaborator::where('user_id', auth()->id())
            ->where('id', $collaboratorId)
            ->where('status', 'pending')
            ->firstOrFail();

        $collaborator->update(['status' => 'active']);

        $this->dispatch('notify', [
            'message' => __('collaboration.notifications.invitation_accepted')
        ]);

        return redirect()->route('case-files.show', $collaborator->case_file_id);
    }

    public function declineInvitation($collaboratorId)
    {
        $collaborator = CaseCollaborator::where('user_id', auth()->id())
            ->where('id', $collaboratorId)
            ->where('status', 'pending')
            ->firstOrFail();

        $collaborator->delete();

        $this->dispatch('notify', [
            'message' => __('collaboration.notifications.invitation_declined')
        ]);
    }

    public function render()
    {
        return view('livewire.case-collaborators.pending-invitations', [
            'pendingInvitations' => CaseCollaborator::where('user_id', auth()->id())
                ->where('status', 'pending')
                ->with('caseFile')
                ->get()
        ]);
    }
}