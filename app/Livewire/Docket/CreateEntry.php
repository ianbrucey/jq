<?php

namespace App\Livewire\Docket;

use App\Models\CaseFile;
use App\Models\DocketEntry;
use App\Models\Party;
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

    #[Rule('required|exists:parties,id')]
    public ?int $filing_party_id = null;

    #[Rule('nullable|exists:parties,id')]
    public ?int $judge_id = null;

    #[Rule('nullable|string|max:255')]
    public ?string $docket_number = null;

    #[Rule('nullable|string|in:' . self::STATUSES)]
    public ?string $status = null;

    // Search properties
    public string $filingPartySearch = '';
    public string $judgeSearch = '';
    public array $filingPartyResults = [];
    public array $judgeResults = [];

    private const ENTRY_TYPES = 'filing,order,hearing,notice,motion,judgment,other';
    private const STATUSES = 'pending,granted,denied,heard,continued,withdrawn';

    #[On('openCreateDocketEntryModal')]
    public function open(): void
    {
        $this->isOpen = true;
        $this->entry_date = now()->format('Y-m-d');
    }

    public function updatedFilingPartySearch()
    {
        if (strlen($this->filingPartySearch) >= 2) {
            $this->filingPartyResults = Party::where('name', 'like', "%{$this->filingPartySearch}%")
                ->orWhere('email', 'like', "%{$this->filingPartySearch}%")
                ->limit(5)
                ->get()
                ->toArray();
        } else {
            $this->filingPartyResults = [];
        }
    }

    public function updatedJudgeSearch()
    {
        if (strlen($this->judgeSearch) >= 2) {
            $this->judgeResults = Party::where('name', 'like', "%{$this->judgeSearch}%")
                ->orWhere('email', 'like', "%{$this->judgeSearch}%")
                ->limit(5)
                ->get()
                ->toArray();
        } else {
            $this->judgeResults = [];
        }
    }

    public function clearFilingPartySearch()
    {
        $this->filingPartySearch = '';
        $this->filingPartyResults = [];
    }

    public function clearJudgeSearch()
    {
        $this->judgeSearch = '';
        $this->judgeResults = [];
    }

    public function selectFilingParty($partyId)
    {
        $this->filing_party_id = $partyId;
        $this->clearFilingPartySearch();
    }

    public function selectJudge($partyId)
    {
        $this->judge_id = $partyId;
        $this->clearJudgeSearch();
    }

    public function removeFilingParty()
    {
        $this->filing_party_id = null;
    }

    public function removeJudge()
    {
        $this->judge_id = null;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $entry = $this->caseFile->docketEntries()->create($validated);

        $this->reset();
        $this->isOpen = false;

        session()->flash('flash.banner', __('docket.entry.created_successfully'));
        $this->dispatch('docket-entry-created');
    }

    public function render(): View
    {
        $filingParty = $this->filing_party_id ? Party::find($this->filing_party_id) : null;
        $judge = $this->judge_id ? Party::find($this->judge_id) : null;

        return view('livewire.docket.create-entry', [
            'entryTypes' => explode(',', self::ENTRY_TYPES),
            'statuses' => explode(',', self::STATUSES),
            'filingParty' => $filingParty,
            'judge' => $judge,
        ]);
    }
}
