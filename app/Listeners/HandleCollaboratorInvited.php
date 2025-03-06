<?php

namespace App\Listeners;

use App\Events\CollaboratorInvited;
use App\Notifications\CollaborationInvite;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleCollaboratorInvited implements ShouldQueue
{
    public function handle(CollaboratorInvited $event)
    {
        $collaborator = $event->collaborator;
        $user = $collaborator->user;

        $user->notify(new CollaborationInvite($collaborator));
    }
}