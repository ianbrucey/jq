<?php

namespace App\Events;

use App\Models\CaseCollaborator;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CollaboratorInvited implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $collaborator;

    public function __construct(CaseCollaborator $collaborator)
    {
        $this->collaborator = $collaborator;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('case.' . $this->collaborator->case_file_id);
    }
}