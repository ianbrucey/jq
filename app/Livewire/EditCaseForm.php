<?php

namespace App\Livewire;

use App\Models\CaseFile;
use Livewire\Component;
use Livewire\Attributes\Rule;

class EditCaseForm extends Component
{
    public CaseFile $caseFile;

    #[Rule('required|min:3|max:255')]
    public $title;

    #[Rule('nullable|max:255')]
    public $reference_number;

    #[Rule('required|min:10')]
    public $summary;

    #[Rule('required|min:10')]
    public $desired_outcome;

    public function mount(CaseFile $caseFile)
    {
        $this->caseFile = $caseFile;
        $this->title = $caseFile->title;
        $this->reference_number = $caseFile->reference_number;
        $this->summary = $caseFile->summary;
        $this->desired_outcome = $caseFile->desired_outcome;
    }

    public function save()
    {
        $validated = $this->validate();

        try {
            $this->caseFile->update($validated);
            
            session()->flash('flash.banner', __('cases.edit.success'));
            session()->flash('flash.bannerStyle', 'success');
            
            return redirect()->route('case-files.show', $this->caseFile);
        } catch (\Exception $e) {
            session()->flash('flash.banner', __('cases.edit.error'));
            session()->flash('flash.bannerStyle', 'danger');
        }
    }

    public function render()
    {
        return view('livewire.edit-case-form');
    }
}