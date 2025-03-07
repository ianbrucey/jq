<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-base-content/80">
            {{ __('collaboration.invitations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-base-100 shadow-xl sm:rounded-lg">
                <livewire:case-collaborators.pending-invitations />
            </div>
        </div>
    </div>
</x-app-layout>