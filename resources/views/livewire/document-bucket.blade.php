<div>
    <div class="space-y-6">
        <!-- Upload Section -->
        <div class="mb-8" x-data="{ isOpen: false }">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-base-content">Upload Documents</h3>
                <button @click="isOpen = !isOpen"
                        class="btn btn-primary btn-sm"
                        :class="{ 'btn-ghost': isOpen }">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         :class="{ 'rotate-180': isOpen }">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7" />
                    </svg>
                    <span x-text="isOpen ? 'Close Uploader' : 'Upload New Documents'"></span>
                </button>
            </div>

            <div x-show="isOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2">
                <livewire:document-uploader :case-file="$caseFile" />
            </div>
        </div>

        <!-- Documents List Section -->
        <div class="border-t border-base-content/10 pt-8">
            <h3 class="text-lg font-medium text-base-content mb-4">Case Documents</h3>
            <livewire:document-list :case-file="$caseFile" />
        </div>
    </div>
</div>
