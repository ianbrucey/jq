<div class="flex flex-col p-4 space-y-3 bg-white rounded-lg shadow dark:bg-gray-800"
     x-data="{
        uploading: false,
        recording: false,
        mediaRecorder: null,
        audioChunks: [],
        allowedMimeTypes: ['audio/wav', 'audio/mp3', 'audio/mpeg', 'audio/m4a', 'audio/mp4', 'audio/x-m4a', 'audio/webm', 'video/webm', 'audio/ogg'],
        maxFileSize: 25 * 1024 * 1024, // 25MB in bytes

        getSupportedMimeType() {
            const types = [
                'audio/webm;codecs=opus',
                'audio/webm',
                'audio/wav',
                'audio/mp3',
                'audio/mpeg',
                'audio/m4a',
                'audio/mp4',
                'audio/x-m4a',
                'audio/ogg'
            ];

            for (const type of types) {
                if (MediaRecorder.isTypeSupported(type)) {
                    return type;
                }
            }
            throw new Error('No supported audio MIME type found in this browser');
        },

        async startRecording() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                const mimeType = this.getSupportedMimeType();

                this.mediaRecorder = new MediaRecorder(stream, {
                    mimeType: mimeType,
                    audioBitsPerSecond: 128000
                });

                this.audioChunks = [];

                this.mediaRecorder.ondataavailable = (event) => {
                    if (event.data.size > 0) {
                        this.audioChunks.push(event.data);
                    }
                };

                this.mediaRecorder.onstop = async () => {
                    const audioBlob = new Blob(this.audioChunks, { type: mimeType });
                    await this.uploadAudioBlob(audioBlob);
                    stream.getTracks().forEach(track => track.stop());
                };

                this.mediaRecorder.start(1000); // Collect data every second
                this.recording = true;
            } catch (error) {
                console.error('Recording error:', error);
                alert('Error accessing microphone: ' + (error.message || 'Unknown error occurred'));
            }
        },

        stopRecording() {
            if (this.mediaRecorder && this.recording) {
                this.mediaRecorder.stop();
                this.recording = false;
            }
        },

        async uploadAudioBlob(blob) {
            if (blob.size > this.maxFileSize) {
                alert(`Recording size (${(blob.size / (1024 * 1024)).toFixed(2)}MB) exceeds maximum allowed size of ${this.maxFileSize / (1024 * 1024)}MB`);
                return;
            }

            this.uploading = true;
            const formData = new FormData();
            const extension = blob.type.includes('webm') ? 'webm' : 'wav';
            formData.append('audio', blob, `recording.${extension}`);

            try {
                const response = await fetch('/transcribe', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    $wire.appendTranscription(data.transcription);
                } else {
                    throw new Error(data.error || 'Unknown error occurred');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error uploading recording: ' + error.message);
            } finally {
                this.uploading = false;
            }
        },

         uploadAudio(event) {
            const file = event.target.files[0];
            if (!file) {
                console.log('No file selected');
                return;
            }

            if (!this.allowedMimeTypes.includes(file.type)) {
                alert('Invalid file type. Please upload an audio file.');
                return;
            }

            if (file.size > this.maxFileSize) {
                alert(`File size (${(file.size / (1024 * 1024)).toFixed(2)}MB) exceeds maximum allowed size of ${this.maxFileSize / (1024 * 1024)}MB`);
                return;
            }

            this.uploading = true;
            const formData = new FormData();
            formData.append('audio', file);

            fetch('/transcribe', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $wire.appendTranscription(data.transcription);
                } else {
                    throw new Error(data.error || 'Unknown error occurred');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error uploading file: ' + error.message);
            })
            .finally(() => {
                this.uploading = false;
                event.target.value = '';
            });
        }
     }">
    <style>
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
    </style>

    {{-- Text Area Container --}}
    <div class="relative">
        <textarea
            wire:model.live="message"
            name="{{ $name }}"
            class="w-full border-gray-300 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
            style="height: {{ $height }};"
            placeholder="Type your message..."></textarea>

        {{-- Transcription Overlay --}}
        <div x-show="uploading"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 flex items-center justify-center bg-orange-500 rounded-lg" style="background: rgba(255, 165, 0, 0.7);">
            <div class="text-center">
                <div class="flex items-center space-x-2 text-white animate-pulse">
                    <svg class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="font-semibold">Converting audio to text...</span>
                </div>
            </div>
        </div>
    </div>
    {{-- Voice Controls --}}
    <div class="flex space-x-2">
        {{-- Voice Recorder Button --}}
        <button type="button"
                @click="recording ? stopRecording() : startRecording()"
                :class="{'bg-red-600 hover:bg-red-700': recording}"
                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-gray-800 border border-transparent rounded-md dark:bg-gray-200 dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
            </svg>
            <span x-text="recording ? 'Stop Recording' : 'Speak'"></span>
        </button>

        {{-- Voice Upload Button --}}
        <input type="file"
               accept="audio/*,video/webm"
               class="hidden"
               id="audio-upload-{{ $name }}"
               @change="uploadAudio($event)" />

        <button type="button"
                @click="document.getElementById('audio-upload-{{ $name }}').click()"
                :disabled="uploading || recording"
                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-gray-800 border border-transparent rounded-md dark:bg-gray-200 dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            <span x-text="uploading ? 'Transcribing...' : 'Voice Note'"></span>
        </button>
    </div>

    {{-- Send Button --}}
{{--    <div class="flex justify">--}}
{{--        <button type="button" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">--}}
{{--            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />--}}
{{--            </svg>--}}
{{--            Send--}}
{{--        </button>--}}
{{--    </div>--}}
</div>
