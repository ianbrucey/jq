<?php

namespace App\Notifications;

use App\Models\CaseCollaborator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CollaborationInvite extends Notification implements ShouldQueue
{
    use Queueable;

    protected $collaborator;

    public function __construct(CaseCollaborator $collaborator)
    {
        $this->collaborator = $collaborator;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $url = route('case.collaboration.accept', [
            'case' => $this->collaborator->case_file_id,
            'token' => encrypt($this->collaborator->id)
        ]);

        return (new MailMessage)
            ->subject('You\'ve Been Invited to Collaborate on a Case')
            ->line('You have been invited to collaborate on a case in Justice Quest.')
            ->line('Role: ' . ucfirst($this->collaborator->role))
            ->action('Accept Invitation', $url)
            ->line('If you did not expect this invitation, you can safely ignore this email.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'case_file_id' => $this->collaborator->case_file_id,
            'role' => $this->collaborator->role,
            'status' => $this->collaborator->status,
            'inviter_id' => auth()->id()
        ];
    }
}