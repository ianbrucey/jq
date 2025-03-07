<?php

namespace App\Notifications;

use App\Models\CaseFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CollaboratorRemovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public CaseFile $caseFile
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('collaboration.notifications.access_revoked_subject'))
            ->line(__('collaboration.notifications.access_revoked_message', [
                'case' => $this->caseFile->title
            ]))
            ->action(__('collaboration.buttons.view_cases'), route('cases.index'))
            ->line(__('collaboration.notifications.thank_you'));
    }

    public function toArray($notifiable): array
    {
        return [
            'case_file_id' => $this->caseFile->id,
            'case_title' => $this->caseFile->title,
            'type' => 'collaborator_removed'
        ];
    }
}