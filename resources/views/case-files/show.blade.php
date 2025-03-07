<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-base-content/80 flex items-center gap-2">
                <span class="text-2xl">📋</span>
                {{ __('cases.details') }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard') }}"
                   class="btn btn-ghost btn-sm">
                    ← {{ __('cases.actions.back_to_dashboard') }}
                </a>
                <a href="{{ route('case-files.docket', $caseFile) }}"
                   class="btn btn-sm btn-ghost hover:btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>{{ __('docket.navigation.view_docket') }}</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <livewire:case-files.show :case-file="$caseFile" />
        </div>
    </div>
</x-app-layout>
