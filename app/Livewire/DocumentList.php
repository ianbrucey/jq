<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CaseFile;
use Livewire\WithPagination;

class DocumentList extends Component
{
    use WithPagination;

    public CaseFile $caseFile;
    
    protected $listeners = ['document-uploaded' => '$refresh'];

    public function mount(CaseFile $caseFile)
    {
        $this->caseFile = $caseFile;
    }

    public function render()
    {
        return view('livewire.document-list', [
            'documents' => $this->caseFile->documents()
                ->latest()
                ->paginate(10)
        ]);
    }
}