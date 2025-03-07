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
                   class="btn btn-sm {{ request()->routeIs('case-files.docket') ? 'btn-primary' : 'btn-ghost' }}">
                    {{ __('docket.navigation.docket') }}
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
