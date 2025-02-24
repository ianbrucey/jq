<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('documentUploader', ({ files = [], titles = [], descriptions = [] } = {}) => ({
            isDropping: false,
            isUploading: false,
            uploadingMessage: 'Preparing upload...',
            pendingUploads: 0,
            files: files,
            titles: titles,
            descriptions: descriptions,
            maxFileSize: 10 * 1024 * 1024,
            allowedTypes: [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'image/jpeg',
                'image/png'
            ],

            onFileDropped(event) {
                this.handleFiles(event.dataTransfer.files);
            },

            onFileInputChanged(event) {
                this.handleFiles(event.target.files);
            },
            handleFiles(fileList) {
                this.isUploading = true;
                this.uploadingMessage = 'Processing files...';
                this.pendingUploads = fileList.length;

                Array.from(fileList).forEach(file => {
                    if (!this.validateFile(file)) {
                        this.pendingUploads--;
                        if (this.pendingUploads === 0) {
                            this.isUploading = false;
                        }
                        return;
                    }

                    // Add isSaving property when creating the file object
                    file.isSaving = false;

                    this.$wire.upload('files', file,
                        (uploadedFile) => {
                            // Use nextTick from Alpine instead
                            this.$nextTick(() => {
                                this.pendingUploads--;
                                this.uploadingMessage = this.pendingUploads > 0
                                    ? `Processing ${this.pendingUploads} remaining files...`
                                    : 'Upload complete!';

                                if (this.pendingUploads === 0) {
                                    setTimeout(() => {
                                        this.isUploading = false;
                                    }, 500);
                                }
                            });
                        },
                        () => {
                            this.uploadingMessage = 'Upload failed!';
                            this.$wire.addError('upload', `Failed to upload ${file.name}`);
                            this.pendingUploads--;
                            if (this.pendingUploads === 0) {
                                setTimeout(() => {
                                    this.isUploading = false;
                                }, 1000);
                            }
                        },
                        (progressEvent) => {
                            const progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                            this.uploadingMessage = `Uploading... ${progress}%`;

                            const fileIndex = this.files.findIndex(f => f.metadata.name === file.name);
                            if (fileIndex !== -1 && progressEvent.total > 0) {
                                this.files[fileIndex].metadata.progress = progress;
                            }
                        }
                    );
                });
            },
            validateFile(file) {
                if (!this.allowedTypes.includes(file.type)) {
                    this.$wire.addError('upload', `Invalid file type: ${file.name}`);
                    return false;
                }

                if (file.size > this.maxFileSize) {
                    this.$wire.addError('upload', `File too large: ${file.name}`);
                    return false;
                }

                return true;
            },

            simulateUpload(index) {
                const file = this.files[index];
                let progress = 0;

                const interval = setInterval(() => {
                    progress += 10;
                    file.progress = progress;

                    if (progress >= 100) {
                        clearInterval(interval);
                    }
                }, 100);
            },

            removeFile(index) {
                this.files.splice(index, 1);
                this.titles.splice(index, 1);
                this.descriptions.splice(index, 1);
                this.$wire.removeFile(index);
            },

            formatFileSize(bytes) {
                if (bytes < 1024) return bytes + ' B';
                else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                else return (bytes / 1048576).toFixed(1) + ' MB';
            }
        }));
    });
</script>
