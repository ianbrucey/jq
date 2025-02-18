<div class="space-y-4">
    <div x-data="{
        isDropping: false,
        removeFile(index) {
            $wire.removeFile(index)
        }
    }"
         x-on:dragover.prevent="isDropping = true"
         x-on:dragleave.prevent="isDropping = false"
         x-on:drop.prevent="
            isDropping = false;
            const files = $event.dataTransfer.files;
            @this.uploadMultiple('files', files)
         "
         class="relative">

        {{-- Dropzone --}}
        <label for="file-upload" class="relative block cursor-pointer">
            <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 transition-all duration-200 ease-in-out"
                 :class="{ 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20': isDropping }">

                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                    <div class="mt-4 text-sm leading-6 text-gray-600 dark:text-gray-400">
                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">Click to upload</span>
                        <span class="pl-1">or drag and drop</span>
                    </div>
                    <p class="text-xs leading-5 text-gray-600 dark:text-gray-400">PDF, DOC, DOCX, JPG, PNG up to 10MB each</p>
                </div>

                <input
                    id="file-upload"
                    wire:model="files"
                    type="file"
                    class="hidden"
                    multiple
                >
            </div>
        </label>

        {{-- File Preview Section --}}
        @if(count($files) > 0)
            <div class="mt-6 space-y-4">
                @foreach($files as $index => $file)
                    <div class="relative flex items-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-3">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <div class="flex-1">
                                    <input type="text"
                                           wire:model="documentTitles.{{ $index }}"
                                           class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           placeholder="Document title">
                                    <input type="text"
                                           wire:model="documentDescriptions.{{ $index }}"
                                           class="mt-2 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           placeholder="Brief description (optional)">
                                </div>
                            </div>
                        </div>
                        <button type="button"
                                x-on:click="removeFile({{ $index }})"
                                class="ml-4 text-gray-400 hover:text-red-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
