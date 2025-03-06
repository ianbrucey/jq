<?php

namespace App\Notifications;

use App\Models\CaseFile;
use App\Models\CaseCollaborator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CollaboratorRoleChangedNotification extends Notification implements ShouldQueue
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
            ->subject('Your case collaboration role has been updated')
            ->line("Your role for the case '{$this->caseFile->title}' has been updated.")
            ->line("New Role: " . ucfirst($this->collaborator->role))
            ->action('View Case', route('cases.show', $this->caseFile))
            ->line('If you have any questions, please contact the case owner.');
    }

    public function toArray($notifiable): array
    {
        return [
            'case_file_id' => $this->caseFile->id,
            'case_title' => $this->caseFile->title,
            'new_role' => $this->collaborator->role,
        ];
    }
}