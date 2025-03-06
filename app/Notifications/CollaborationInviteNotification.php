<?php

namespace App\Notifications;

use App\Models\CaseFile;
use App\Models\CaseCollaborator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CollaborationInviteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public CaseFile $caseFile,
        public CaseCollaborator $collaborator
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
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
        ];
    }
}