<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <h2 class="text-xl font-semibold leading-tight text-base-content">
                Address Book
            </h2>
            <a href=""
               class="btn btn-primary btn-sm w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Contact
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="bg-base-100/50 dark:bg-neutral-focus/50 backdrop-blur-xl shadow-xl sm:rounded-lg p-8">
                <livewire:address-book.party-directory />
            </div>
        </div>
    </div>
</x-app-layout>
