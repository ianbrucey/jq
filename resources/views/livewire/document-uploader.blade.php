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

    {{-- Add this style section at the top of your component --}}
    <style>
        .upload-pulse-ring {
            width: 80px;
            height: 80px;
            border: 4px solid hsl(var(--p));
            border-radius: 50%;
            position: relative;
            animation: upload-pulse 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }

        .upload-pulse-ring:after {
            content: '';
            position: absolute;
            width: 80px;
            height: 80px;
            border: 4px solid hsl(var(--p));
            border-radius: 50%;
            animation: upload-pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }

        @keyframes upload-pulse {
            0% { transform: scale(0.3); opacity: 0.9; }
            80%, 100% { transform: scale(1); opacity: 0; }
        }

        @keyframes upload-pulse-ring {
            0% { transform: scale(0.3); opacity: 0.9; }
            80%, 100% { transform: scale(1.4); opacity: 0; }
        }
    </style>

    {{-- Dropzone --}}
    <label for="file-upload" class="relative block cursor-pointer">
        <div class="relative p-8 transition-all duration-200 ease-in-out border-2 border-dashed rounded-lg border-base-content/20"
             :class="{ 'border-primary bg-primary/5': isDropping }">
            <div class="text-center">
                <svg class="w-12 h-12 mx-auto text-base-content/50" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="mt-4 text-sm leading-6 text-base-content">
                    <span class="font-semibold text-primary">Click to upload</span>
                    <span class="pl-1">or drag and drop</span>
                </div>
                <p class="text-xs leading-5 text-base-content/60">PDF, DOC, DOCX, JPG, PNG up to 10MB each</p>
            </div>

            <input
                id="file-upload"
                type="file"
                class="hidden"
                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                multiple
                x-on:change="onFileInputChanged($event)"
            >
        </div>
    </label>

    {{-- File Preview Section --}}
    <div class="mt-6 space-y-4" x-show="files.length > 0">
        {{-- Save All Button --}}
        <div class="flex justify-end">
            <button type="button"
                    wire:click="saveAllDocuments"
                    wire:loading.attr="disabled"
                    wire:target="saveAllDocuments"
                    class="btn btn-primary"
                    :class="{ 'loading': $wire.get('isSavingAll') }">
                <span wire:loading.remove wire:target="saveAllDocuments">
                    Save All Documents
                </span>
                <span wire:loading wire:target="saveAllDocuments">
                    Saving Documents...
                </span>
            </button>
        </div>

        <template x-for="(file, index) in files" :key="index">
            <div class="relative flex items-center p-4 border rounded-lg shadow-sm bg-base-100 border-base-content/20">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-base-content" x-text="file.metadata.name"></span>
                        <span class="text-xs text-base-content/60" x-text="formatFileSize(file.metadata.size)"></span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="relative h-2 mt-2 overflow-hidden rounded-full bg-base-200" x-show="file.progress < 100">
                        <div class="absolute top-0 left-0 h-full transition-all duration-300 bg-primary"
                             :style="`width: ${file.progress}%`"></div>
                    </div>

                    {{-- Title Input --}}
                    <div class="mt-2">
                        <input type="text"
                               x-model="titles[index]"
                               class="w-full input input-bordered"
                               placeholder="Document Title (optional)">
                    </div>

                    {{-- Description Input --}}
                    <div class="mt-2">
                        <textarea x-model="descriptions[index]"
                                class="w-full textarea textarea-bordered"
                                placeholder="Document Description (optional)"></textarea>
                    </div>
                </div>

                {{-- Remove Button --}}
                <button type="button"
                        x-on:click="removeFile(index)"
                        class="ml-4 text-base-content/60 hover:text-error">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Save Button --}}
                <div class="ml-4">
                    <template x-if="!file.isSaving">
                        <button type="button"
                                @click="file.isSaving = true; $wire.saveDocument(index); setTimeout(() => file.isSaving = false, 5000)"
                                class="btn btn-primary btn-sm">
                            Save Document
                        </button>
                    </template>
                    <template x-if="file.isSaving">
                        <button class="btn btn-primary btn-sm" disabled>
                            <span class="loading loading-spinner loading-sm"></span>
                            Saving...
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
</div>
