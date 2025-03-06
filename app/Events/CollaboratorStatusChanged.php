<?php

namespace App\Events;

use App\Models\CaseCollaborator;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CollaboratorStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $collaborator;
    public $previousStatus;

    public function __construct(CaseCollaborator $collaborator, string $previousStatus)
    {
        $this->collaborator = $collaborator;
        $this->previousStatus = $previousStatus;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('case.' . $this->collaborator->case_file_id);
    }
}