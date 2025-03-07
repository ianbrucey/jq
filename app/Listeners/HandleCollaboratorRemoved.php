<?php

namespace App\Listeners;

use App\Events\CollaboratorRemoved;
use App\Notifications\CollaboratorRemovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleCollaboratorRemoved implements ShouldQueue
{
    public function handle(CollaboratorRemoved $event): void
    {
        $event->user->notify(new CollaboratorRemovedNotification($event->caseFile));
    }
}