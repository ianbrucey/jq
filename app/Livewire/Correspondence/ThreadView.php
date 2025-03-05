<?php

namespace App\Livewire\Correspondence;

use App\Models\Thread;
use Livewire\Component;
use Livewire\Attributes\On;

class ThreadView extends Component
{
    public Thread $thread;
    public $showAddCommunicationModal = false;

    #[On('communicationDeleted')]
    public function refreshThread()
    {
        $this->thread->refresh();
    }

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
