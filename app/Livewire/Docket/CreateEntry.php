<?php

namespace App\Livewire\Docket;

use App\Models\CaseFile;
use App\Models\DocketEntry;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;

class CreateEntry extends Component
{
    public CaseFile $caseFile;
    public bool $isOpen = false;

    #[Rule('required|date|before_or_equal:today')]
    public ?string $entry_date = null;

    #[Rule('required|string|in:' . self::ENTRY_TYPES)]
    public ?string $entry_type = null;

    #[Rule('required|string|max:255')]
    public ?string $title = null;

    #[Rule('nullable|string')]
    public ?string $description = null;

    #[Rule('nullable|string|max:255')]
    public ?string $filing_party = null;

    #[Rule('nullable|string|max:255')]
    public ?string $judge = null;

    #[Rule('nullable|string|max:255')]
    public ?string $docket_number = null;

    #[Rule('nullable|string|in:' . self::STATUSES)]
    public ?string $status = null;

    private const ENTRY_TYPES = 'filing,order,hearing,notice,motion,judgment,other';
    private const STATUSES = 'pending,granted,denied,heard,continued,withdrawn';

    #[On('openCreateDocketEntryModal')]
    public function open(): void
    {
        $this->isOpen = true;
        $this->entry_date = now()->format('Y-m-d');
    }

    public function save(): void
    {
        $validated = $this->validate();
        
        $entry = $this->caseFile->docketEntries()->create($validated);

        $this->reset();
        $this->isOpen = false;

        session()->flash('flash.banner', __('docket.entry.created_successfully'));
        session()->flash('flash.bannerStyle', 'success');

        $this->dispatch('docketEntryCreated', $entry->id);
    }

    public function render(): View
    {
        return view('livewire.docket.create-entry', [
            'entryTypes' => DocketEntry::ENTRY_TYPES,
            'statuses' => DocketEntry::STATUSES,
        ]);
    }
}