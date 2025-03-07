<?php

namespace App\Livewire\Docket;

use App\Models\CaseFile;
use App\Models\DocketEntry;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class DocketDashboard extends Component
{
    use WithPagination;

    public CaseFile $caseFile;

    public string $search = '';
    public ?string $entryType = null;
    public ?string $status = null;
    public ?string $dateRange = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'entryType' => ['except' => ''],
        'status' => ['except' => ''],
        'dateRange' => ['except' => ''],
    ];

    protected $listeners = [
        'docketEntryCreated' => '$refresh',
        'docketEntryUpdated' => '$refresh',
        'docketEntryDeleted' => '$refresh',
    ];

    public function mount(CaseFile $caseFile): void
    {
        $this->caseFile = $caseFile;
    }

    #[Computed]
    public function entries(): Builder
    {
        return DocketEntry::query()
            ->where('case_file_id', $this->caseFile->id)
            ->when($this->search, fn($query) =>
                $query->where(function($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%")
                      ->orWhere('docket_number', 'like', "%{$this->search}%");
                })
            )
            ->when($this->entryType, fn($query) =>
                $query->where('entry_type', $this->entryType)
            )
            ->when($this->status, fn($query) =>
                $query->where('status', $this->status)
            )
            ->orderBy('entry_date', 'desc')
            ->orderBy('created_at', 'desc');
    }

    public function showCreateModal(): void
    {
        $this->dispatch('openCreateDocketEntryModal');
    }

    public function render(): View
    {
        return view('livewire.docket.dashboard', [
            'docketEntries' => $this->entries->paginate(10),
            'entryTypes' => DocketEntry::ENTRY_TYPES,
            'statuses' => DocketEntry::STATUSES,
        ]);
    }
}
