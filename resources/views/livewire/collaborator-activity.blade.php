<div class="space-y-4">
    <div class="flex flex-col sm:flex-row gap-4 bg-base-200 p-4 rounded-lg">
        <div class="form-control flex-1">
            <label class="label">
                <span class="label-text">Filter by User</span>
            </label>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="filterUser" 
                class="input input-bordered w-full"
                placeholder="Search by user name"
            >
        </div>

        <div class="form-control flex-1">
            <label class="label">
                <span class="label-text">Filter by Action</span>
            </label>
            <select wire:model.live="filterAction" class="select select-bordered w-full">
                <option value="">All Actions</option>
                @foreach($actionTypes as $action)
                    <option value="{{ $action }}">{{ ucfirst($action) }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-control flex-1">
            <label class="label">
                <span class="label-text">Date Range</span>
            </label>
            <input 
                type="text" 
                wire:model.live="dateRange" 
                class="input input-bordered w-full"
                placeholder="YYYY-MM-DD to YYYY-MM-DD"
            >
        </div>

        <div class="form-control flex-1">
            <label class="label">
                <span class="label-text">&nbsp;</span>
            </label>
            <button 
                class="btn btn-ghost"
                wire:click="resetFilters"
            >
                Reset Filters
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Details</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activityLogs as $log)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="avatar placeholder">
                                    <div class="bg-neutral text-neutral-content rounded-full w-8">
                                        <span class="text-xs">{{ substr($log->user->name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">{{ $log->user->name }}</div>
                                    <div class="text-sm opacity-50">{{ $log->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-outline">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td>
                            <div class="text-sm">
                                @if($log->metadata)
                                    @foreach($log->metadata as $key => $value)
                                        <div><span class="font-medium">{{ ucfirst($key) }}:</span> {{ $value }}</div>
                                    @endforeach
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">{{ $log->created_at->diffForHumans() }}</div>
                            <div class="text-xs opacity-50">{{ $log->created_at->format('Y-m-d H:i:s') }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <div class="text-gray-500">No activity logs found</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $activityLogs->links() }}
    </div>
</div>