{{--
Document Uploader Component
--------------------------

A Livewire component that provides a drag-and-drop interface for uploading documents to a case file.
This component integrates with Alpine.js for frontend interactivity and Livewire for backend communication.

Features:
- Drag and drop file upload
- Multiple file support
- File type validation (PDF, DOC, DOCX, JPG, PNG)
- File size validation (max 10MB)
- Progress indication
- Title and description fields for each document
- Individual and batch save options

Properties (Livewire):
- $files: Temporary array for newly uploaded files
- $queuedFiles: Array of files pending upload
- $documentTitles: Array of titles for queued files
- $documentDescriptions: Array of descriptions for queued files
- $caseFile: The associated CaseFile model instance

Alpine.js Component Structure:
- documentUploader
  - isDropping: Boolean flag for drag state
  - files: Array of file objects with metadata
  - titles: Array of document titles
  - descriptions: Array of document descriptions
  - maxFileSize: Maximum allowed file size (10MB)
  - allowedTypes: Array of allowed MIME types

Events:
- dragover: Triggers dropping state
- dragleave: Removes dropping state
- drop: Handles file drop
- change: Handles file input change

Methods:
- onFileDropped: Handles dropped files
- onFileInputChanged: Handles files selected via input
- handleFiles: Processes file list
- validateFile: Validates file type and size
- simulateUpload: Shows upload progress animation
- removeFile: Removes file from queue
- formatFileSize: Formats byte size to human-readable string

Integration Points:
- Livewire: Backend communication for file processing
- Alpine.js: Frontend interactivity and state management
- DocumentService: Backend service for document storage
- ProcessDocumentJob: Async document processing

Usage:
<livewire:document-uploader :case-file="$caseFile" />
--}}

<div class="space-y-4"
    x-data="documentUploader({
        files: $wire.entangle('queuedFiles'),
        titles: $wire.entangle('documentTitles'),
        descriptions: $wire.entangle('documentDescriptions')
    })"
    x-on:dragover.prevent="isDropping = true"
    x-on:dragleave.prevent="isDropping = false"
    x-on:drop.prevent="onFileDropped($event)"
    class="relative">

    {{-- Upload Progress Overlay --}}
    <div x-show="isUploading"
         class="fixed inset-0 z-50 flex items-center justify-center bg-base-100/50 backdrop-blur-sm">
        <div class="flex flex-col items-center space-y-4">
            <div class="upload-pulse-ring"></div>
            <p class="text-base-content/70" x-text="uploadingMessage"></p>
        </div>
    </div>

    {{-- File Upload Area --}}
    <label for="file-upload"
           class="relative block w-full rounded-lg border-2 border-dashed border-base-content/20 p-12 text-center hover:border-base-content/40 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
           :class="{ 'border-primary': isDropping }">
        <div class="flex flex-col items-center justify-center">
            <svg class="w-12 h-12 mx-auto text-base-content/50" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="mt-4 text-sm leading-6 text-base-content">
                <span class="font-semibold text-primary">{{ __('documents.click_to_upload') }}</span>
                <span class="pl-1">{{ __('documents.or_drag_and_drop') }}</span>
            </div>
            <p class="text-xs leading-5 text-base-content/60">{{ __('documents.allowed_file_types') }}</p>
        </div>

        <input
            id="file-upload"
            type="file"
            class="hidden"
            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
            multiple
            x-on:change="onFileInputChanged($event)"
        >
    </label>

    {{-- File Preview Section --}}
    <div class="mt-6 space-y-4" x-show="files.length > 0">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium text-base-content">{{ __('documents.upload_documents_title') }}</h3>
            <button type="button"
                    wire:click="saveAllDocuments"
                    wire:loading.attr="disabled"
                    wire:target="saveAllDocuments"
                    class="btn btn-primary"
                    :class="{ 'loading': $wire.get('isSavingAll') }">
                <span wire:loading.remove wire:target="saveAllDocuments">
                    {{ __('documents.save_all_documents') }}
                </span>
                <span wire:loading wire:target="saveAllDocuments">
                    {{ __('documents.saving_documents') }}
                </span>
            </button>
        </div>

        <template x-for="(file, index) in files" :key="index">
            <div class="flex items-start p-4 space-x-4 bg-base-200 rounded-lg">
                <div class="flex-1">
                    <div class="mt-2 text-sm text-base-content/60 p-3">
                        <span class="text-sm font-medium text-base-content p-2 bg-accent rounded-full" x-text="file.metadata.name"></span>
                        <span>{{ __('documents.file_size_label') }}: </span>
                        <span x-text="formatFileSize(file.metadata.size)"></span>
                    </div>
                    <div class="mb-2">
                        <input type="text"
                               x-model="titles[index]"
                               class="w-full input input-bordered"
                               placeholder="{{ __('documents.document_title_placeholder') }}">
                        <div class="mt-1 flex items-center gap-2 text-sm text-info/80">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ __('documents.ai_assist_notice') }}</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <textarea x-model="descriptions[index]"
                                class="w-full textarea textarea-bordered"
                                placeholder="{{ __('documents.document_description_placeholder') }}"></textarea>
                    </div>
                </div>
                <button type="button"
                        x-on:click="removeFile(index)"
                        class="ml-4 text-base-content/60 hover:text-error"
                        :title="__('documents.remove_document')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="ml-4">
                    <template x-if="!file.isSaving">
                        <button type="button"
                                @click="file.isSaving = true; $wire.saveDocument(index); setTimeout(() => file.isSaving = false, 5000)"
                                class="btn btn-primary btn-sm">
                            {{ __('documents.save_document') }}
                        </button>
                    </template>
                    <template x-if="file.isSaving">
                        <button class="btn btn-primary btn-sm" disabled>
                            <span class="loading loading-spinner loading-sm"></span>
                            {{ __('documents.saving') }}
                        </button>
                    </template>
                </div>
            </div>
        </template>
    </div>

    {{-- Error Messages --}}
    @error('upload')
        <div class="mt-4 text-sm text-error">
            {{ $message }}
        </div>
    @enderror

    {{-- Document List Section --}}
    @if($showDocumentList)
        <div class="border-t border-base-content/10 pt-8 mt-8">
            <h3 class="text-lg font-medium text-base-content mb-4">{{ __('documents.uploaded_documents') }}</h3>
            <livewire:document-list :case-file="$caseFile" />
        </div>
    @endif
</div>
