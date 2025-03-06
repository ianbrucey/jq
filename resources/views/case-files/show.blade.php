<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-base-content/80">
                {{ __('cases.details') }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard') }}"
                   class="btn btn-ghost btn-sm">
                    ← {{ __('cases.actions.back_to_dashboard') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-base-100 shadow-xl sm:rounded-lg">
                <!-- Main Case Information -->
                <div class="p-6 border-b border-base-content/10">
                    <div class="flex justify-between items-start">
                        <div class="space-y-1">
                            <h3 class="text-2xl font-medium text-base-content">
                                {{ $caseFile->title }}
                            </h3>
                            <p class="text-base text-base-content/60">
                                {{ __('cases.case_number') }}: {{ $caseFile->case_number ?: __('cases.status.not_assigned') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('case-files.edit', $caseFile) }}"
                               class="btn btn-neutral btn-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('cases.actions.edit') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Case Details Grid -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Desired Outcome -->
                    <div class="card bg-base-200">
                        <div class="card-body">
                            <h4 class="card-title text-base-content">{{ __('cases.desired_outcome') }}</h4>
                            <p class="text-base-content/70">{{ $caseFile->desired_outcome }}</p>
                        </div>
                    </div>

                    <!-- Status and Important Dates -->
                    <div class="card bg-base-200">
                        <div class="card-body">
                            <h4 class="card-title text-base-content">{{ __('cases.status.title') }}</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-base-content/70">{{ __('cases.status.created') }}:</span>
                                    <span class="text-base-content">{{ $caseFile->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-base-content/70">{{ __('cases.status.last_updated') }}:</span>
                                    <span class="text-base-content">{{ $caseFile->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="p-6 bg-base-200 border-t border-base-content/10">
                    <h4 class="text-lg font-medium text-base-content mb-4">{{ __('cases.quick_actions.title') }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Documents Section -->
                        <div class="card bg-base-100 hover:bg-base-300 transition-colors">
                            <div class="card-body">
                                <h5 class="card-title text-base-content flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ __('cases.quick_actions.documents.title') }}
                                </h5>
                                <p class="text-sm text-base-content/60">{{ __('cases.quick_actions.documents.description') }}</p>
                                <div class="card-actions justify-end">
                                    <a href="{{ route('case-files.documents.index', $caseFile) }}"
                                       class="btn btn-primary btn-sm">
                                        {{ __('cases.quick_actions.documents.action') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Correspondences Section -->
                        <div class="card bg-base-100 hover:bg-base-300 transition-colors">
                            <div class="card-body">
                                <h5 class="card-title text-base-content flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z" />
                                    </svg>
                                    {{ __('cases.quick_actions.correspondences.title') }}
                                </h5>
                                <p class="text-sm text-base-content/60">{{ __('cases.quick_actions.correspondences.description') }}</p>
                                <div class="card-actions justify-end">
                                    <a href="{{ route('case-files.correspondences.index', $caseFile) }}"
                                       class="btn btn-primary btn-sm">
                                        {{ __('cases.quick_actions.correspondences.action') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Parties Section -->
                        <div class="card bg-base-100 hover:bg-base-300 transition-colors">
                            <div class="card-body">
                                <h5 class="card-title text-base-content flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ __('cases.quick_actions.parties.title') }}
                                </h5>
                                <p class="text-sm text-base-content/60">{{ __('cases.quick_actions.parties.description') }}</p>
                                <div class="card-actions justify-end">
                                    <a href="{{ route('address-book.index') }}"
                                       class="btn btn-primary btn-sm">
                                        {{ __('cases.quick_actions.parties.action') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
