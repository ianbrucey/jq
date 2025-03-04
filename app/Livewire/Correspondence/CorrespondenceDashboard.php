<?php

namespace App\Livewire\Correspondence;

use App\Models\CaseFile;
use App\Models\Thread;
use Livewire\Component;
use Livewire\WithPagination;

class CorrespondenceDashboard extends Component
{
    use WithPagination;

    public CaseFile $caseFile;
    public ?Thread $selectedThread = null;  // Change to Thread type
    public $search = '';

    protected $listeners = [
        'threadCreated' => '$refresh',
        'threadSelected' => 'selectThread'
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function showCreateModal()
    {
        $this->dispatch('openCreateThreadModal');
    }

    public function selectThread($threadId)
    {
        $this->selectedThread = Thread::find($threadId);  // Load the actual Thread model
    }

    public function render()
    {
        $threads = $this->caseFile->threads()
            ->with(['communications' => function($query) {
                $query->latest('sent_at')->limit(1);
            }])
            ->when($this->search, function($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhereHas('communications', function($query) {
                        $query->where('content', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(5);

        return view('livewire.correspondence.correspondence-dashboard', [
            'threads' => $threads
        ]);
    }
}
