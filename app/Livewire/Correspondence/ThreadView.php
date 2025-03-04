<?php

namespace App\Livewire\Correspondence;

use App\Models\Thread;
use Livewire\Component;

class ThreadView extends Component
{
    public Thread $thread;
    public $showAddCommunication = false;

    protected $listeners = [
        'communicationAdded' => '$refresh'
    ];

    public function mount(Thread $thread)
    {
        $this->thread = $thread->load(['communications' => function($query) {
            $query->with(['participants', 'documents'])
                  ->orderBy('sent_at', 'asc');
        }]);
    }

    public function render()
    {
        return view('livewire.correspondence.thread-view');
    }
}