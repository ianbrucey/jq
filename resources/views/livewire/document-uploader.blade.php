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
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        Our AI will automatically analyze your documents to generate titles and summaries. You can optionally provide your own or edit the AI-generated ones later.
                    </p>
                </div>
            </div>
        </div>

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
            <template x-for="(file, index) in files" :key="index">
                <div class="relative flex items-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-3">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="file.name"></span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400" x-text="formatFileSize(file.size)"></span>
                                </div>
                                <div x-show="fileErrors[file.name]" class="text-xs text-red-500 mt-1" x-text="fileErrors[file.name]"></div>
                                <div class="mt-2 space-y-3">
                                    <div class="relative">
                                        <input type="text"
                                               :name="'documentTitles.' + index"
                                               class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                               placeholder="Custom title (optional - AI will generate if left empty)">
                                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            Leave blank for AI-generated title
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <textarea
                                            :name="'documentDescriptions.' + index"
                                            rows="2"
                                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Custom summary (optional - AI will analyze and summarize if left empty)"></textarea>
                                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            Leave blank for AI-generated summary
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button"
                            @click="removeFile(index)"
                            class="ml-4 text-gray-400 hover:text-red-500">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
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
