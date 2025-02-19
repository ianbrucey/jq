<?php

namespace App\Http\Livewire;

use App\Models\CaseFile;
use Livewire\Component;

class DocumentBucket extends Component
{
    public CaseFile $caseFile;

    public function mount(CaseFile $caseFile)
    {
        $this->caseFile = $caseFile;
    }

    public function render()
    {
        return view('livewire.document-bucket');
    }
}