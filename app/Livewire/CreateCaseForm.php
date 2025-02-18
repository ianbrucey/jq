<?php

namespace App\Livewire;

use App\Models\CaseFile;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CreateCaseForm extends Component
{
    public $step = 1;
    public $title;
    public $case_number;
    public $desired_outcome;
    public $summary;
    public $caseFile = null;

    #[On('voice-message-updated')]
    public function handleVoiceMessageUpdated($name, $message)
    {
        if ($name === 'desired_outcome') {
            $this->desired_outcome = $message;
        }
    }

    protected $rules = [
        'title' => 'required|string|max:255',
        'case_number' => 'nullable|string|max:255',
        'desired_outcome' => 'required|string|max:255',
        'summary' => 'nullable|string',
    ];

    public function saveInitialDetails()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'case_number' => 'nullable|string|max:255',
            'desired_outcome' => 'required|string',
        ]);

        $dataToSave = [
            'title' => $this->title,
            'case_number' => $this->case_number,
            'desired_outcome' => $this->desired_outcome,
        ];
        // dd($dataToSave);
        $this->caseFile = Auth::user()->caseFiles()->create($dataToSave);

        $this->step = 2;
    }

    public function skipAdditionalDetails()
    {
        return redirect()->route('dashboard');
    }

    public function saveAdditionalDetails()
    {
        if (!$this->caseFile) {
            return redirect()->route('dashboard');
        }

        $this->validate([
            'summary' => 'nullable|string',
        ]);

        $this->caseFile->update([
            'summary' => $this->summary,
        ]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.create-case-form');
    }
}
