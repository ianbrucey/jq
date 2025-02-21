<div class="space-y-4">
    <div x-data="{
        isDropping: false,
        files: [],
        fileErrors: {},
        maxFileSize: 10 * 1024 * 1024, // 10MB in bytes
        allowedTypes: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'],

        handleFiles(fileList) {
            Array.from(fileList).forEach(file => {
                // Validate file type
                if (!this.allowedTypes.includes(file.type)) {
                    this.fileErrors[file.name] = `Invalid file type. Allowed types: PDF, DOC, DOCX, JPG, PNG`;
                    return;
                }

                // Validate file size
                if (file.size > this.maxFileSize) {
                    this.fileErrors[file.name] = `File size exceeds 10MB limit`;
                    return;
                }

                // Add file if it passes validation
                if (!this.files.some(f => f.name === file.name)) {
                    this.files.push(file);
                    $wire.addFile(file);
                }
            });
        },

        removeFile(index) {
            this.files.splice(index, 1);
            $wire.removeFile(index);
        },

        formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            else return (bytes / 1048576).toFixed(1) + ' MB';
        }
    }"
    x-on:dragover.prevent="isDropping = true"
    x-on:dragleave.prevent="isDropping = false"
    x-on:drop.prevent="
        isDropping = false;
        handleFiles($event.dataTransfer.files)
    "
    class="relative">

        {{-- AI Assistant Info Banner --}}
        <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/50 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-secondary dark:text-blue-50">
                        Our AI will automatically analyze your documents to generate titles and summaries. You can optionally provide your own or edit the AI-generated ones later.
                    </p>
                </div>
            </div>
        </div>

        {{-- Dropzone --}}
        <label for="file-upload" class="relative block cursor-pointer">
            <div class="relative border-2 border-dashed border-base-content/30 dark:border-base-content/60 rounded-lg p-8 transition-all duration-500 ease-in-out"
                 :class="{ 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20': isDropping }">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-base-content/60" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="mt-4 text-sm leading-6 text-base-content/60 dark:text-base-content/60">
                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">Click to upload</span>
                        <span class="pl-1">or drag and drop</span>
                    </div>
                    <p class="text-xs leading-5 text-base-content/60 dark:text-base-content/60">PDF, DOC, DOCX, JPG, PNG up to 10MB each</p>
                </div>

                <input
                    id="file-upload"
                    type="file"
                    class="hidden"
                    multiple
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                    @change="handleFiles($event.target.files)"
                >
            </div>
        </label>

        {{-- File Preview Section --}}
        <div class="mt-6 space-y-4" x-show="files.length > 0">
            {{-- Save All Button --}}
            <div class="flex justify-end">
                <button type="button"
                        @click="$wire.saveAllDocuments()"
                        class="btn btn-primary">
                    Save All Documents
                </button>
            </div>

            <template x-for="(file, index) in files" :key="index">
                <div x-show="files.includes(file)"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="relative flex flex-col p-4 bg-base-100 dark:bg-neutral-focus rounded-lg shadow-sm border border-base-content/20">
                    <div class="flex items-center">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-3">
                                <svg class="h-8 w-8 text-base-content/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-base-content dark:text-base-content/90" x-text="file.name"></span>
                                        <span class="text-xs text-base-content/50 dark:text-base-content/60" x-text="formatFileSize(file.size)"></span>
                                    </div>
                                    <div x-show="fileErrors[file.name]" class="text-xs text-red-500 mt-1" x-text="fileErrors[file.name]"></div>
                                    <div class="mt-2 space-y-3">
                                        <div class="relative">
                                            <input type="text"
                                                   :name="'documentTitles.' + index"
                                                   class="block w-full border-base-content/30 dark:border-base-content/70 dark:bg-neutral-focus dark:text-base-content/70 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                   placeholder="Enter title">
                                            <div class="mt-1 text-xs text-base-content/50 dark:text-base-content/60">
                                                Leave blank for AI-generated title
                                            </div>
                                        </div>

                                        <div class="relative">
                                            <textarea
                                                :name="'documentDescriptions.' + index"
                                                rows="2"
                                                class="block w-full border-base-content/30 dark:border-base-content/70 dark:bg-neutral-focus dark:text-base-content/70 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                placeholder="Enter summary"></textarea>
                                            <div class="mt-1 text-xs text-base-content/50 dark:text-base-content/60">
                                                Leave blank for AI-generated summary
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button"
                                @click="removeFile(index)"
                                class="ml-4 text-base-content/60 hover:text-red-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Save Button --}}
                    <div class="mt-4 flex justify-end">
                        <button type="button"
                                @click="$wire.saveDocument(index)"
                                class="btn btn-primary btn-sm">
                            Save Document
                        </button>
                    </div>
                </div>
            </template>
        </div>

        {{-- Error Messages --}}
        <div x-show="Object.keys(fileErrors).length > 0" class="mt-4">
            <template x-for="(error, fileName) in fileErrors" :key="fileName">
                <div class="text-sm text-red-600 dark:text-red-400">
                    <span x-text="fileName + ': ' + error"></span>
                </div>
            </template>
        </div>
    </div>
</div>
