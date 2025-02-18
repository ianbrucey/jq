<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TranscriptionCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $transcription;

    public function __construct($userId, $transcription)
    {
        $this->userId = $userId;
        $this->transcription = $transcription;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('transcriptions.' . $this->userId);
    }

    public function broadcastAs(): string
    {
        return 'TranscriptionCompleted';
    }

    public function broadcastWith(): array
    {
        return [
            'transcription' => $this->transcription
        ];
    }
}
