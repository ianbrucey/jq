<?php

namespace App\Livewire;

use App\Models\CaseFile;
use App\Models\CaseAccessLog;
use Livewire\Component;
use Livewire\WithPagination;

class CollaboratorActivity extends Component
{
    use WithPagination;

    public CaseFile $caseFile;
    public ?string $filterUser = null;
    public ?string $filterAction = null;
    public ?string $dateRange = null;

    protected $queryString = [
        'filterUser' => ['except' => ''],
        'filterAction' => ['except' => ''],
        'dateRange' => ['except' => ''],
    ];

    public function getActivityLogsProperty()
    {
        return CaseAccessLog::query()
            ->where('case_file_id', $this->caseFile->id)
            ->when($this->filterUser, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', "%{$this->filterUser}%");
                });
            })
            ->when($this->filterAction, function ($query) {
                $query->where('action', $this->filterAction);
            })
            ->when($this->dateRange, function ($query) {
                [$start, $end] = explode(' to ', $this->dateRange . ' to ');
                $query->whereBetween('created_at', [
                    $start,
                    $end ?: now()
                ]);
            })
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function resetFilters()
    {
        $this->reset(['filterUser', 'filterAction', 'dateRange']);
    }

    public function render()
    {
        return view('livewire.collaborator-activity', [
            'activityLogs' => $this->activityLogs,
            'actionTypes' => CaseAccessLog::distinct('action')->pluck('action'),
        ]);
    }
}