<?php

namespace App\Notifications;

use App\Models\CaseFile;
use App\Models\CaseCollaborator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CollaborationInviteNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public function __construct(
        public CaseFile $caseFile,
        public CaseCollaborator $collaborator
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("You've been invited to collaborate on a case")
            ->line("You've been invited to collaborate on case: {$this->caseFile->title}")
            ->line("Role: " . ucfirst($this->collaborator->role))
            ->action('View Case', route('cases.show', $this->caseFile))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable): array
    {
        return [
            'case_file_id' => $this->caseFile->id,
            'case_title' => $this->caseFile->title,
            'role' => $this->collaborator->role,
            'collaborator_id' => $this->collaborator->id,
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'case_file_id' => $this->caseFile->id,
            'case_title' => $this->caseFile->title,
            'role' => $this->collaborator->role,
            'type' => 'collaboration_invite'
        ]);
    }
}
