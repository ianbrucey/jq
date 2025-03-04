<?php

namespace App\Livewire\Correspondence;

use App\Models\CaseFile;
use Livewire\Component;

class CreateThreadModal extends Component
{
    public CaseFile $caseFile;
    public $show = false;
    public $title = '';

    protected $listeners = ['openCreateThreadModal' => 'showModal'];

    protected $rules = [
        'title' => 'required|string|max:255',
    ];

    public function showModal()
    {
        $this->show = true;
    }

    public function save()
    {
        $this->validate();

        $thread = $this->caseFile->threads()->create([
            'title' => $this->title,
            'status' => 'open',
            'created_by' => auth()->id(),
        ]);

        $this->reset('title');
        $this->show = false;
        $this->dispatch('threadCreated', $thread->id);
    }

    public function render()
    {
        return view('livewire.correspondence.create-thread-modal');
    }
}
