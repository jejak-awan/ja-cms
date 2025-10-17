{{-- Alpine.js Image Upload Component --}}
<div 
    x-data="imageUpload('{{ $name ?? 'image' }}', '{{ $action ?? '/api/upload' }}')"
    class="w-full"
>
    {{-- File Input (Hidden) --}}
    <input 
        type="file"
        x-ref="fileInput"
        @change="handleFileSelect"
        accept="image/*"
        class="hidden"
    >

    {{-- Upload Area --}}
    <div 
        @drop.prevent="handleDrop"
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        :class="{'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-700': isDragging, 'border-gray-300 dark:border-gray-600': !isDragging}"
        class="border-2 border-dashed rounded-lg p-8 text-center transition"
    >
        {{-- Dropzone Content --}}
        <template x-if="!preview">
            <div class="space-y-3">
                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33A3 3 0 0116.5 19.5H6.75z"></path>
                </svg>

                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        Drag and drop your image here, or
                        <button 
                            @click="$refs.fileInput.click()"
                            type="button"
                            class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
                        >
                            browse
                        </button>
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG, GIF up to 5MB</p>
                </div>
            </div>
        </template>

        {{-- Preview --}}
        <template x-if="preview && !uploading">
            <div class="space-y-4">
                <div class="relative inline-block">
                    <img 
                        :src="preview" 
                        :alt="fileName"
                        class="max-h-64 rounded-lg"
                    >
                    <button 
                        @click="clearPreview()"
                        type="button"
                        class="absolute -top-3 -right-3 bg-red-600 text-white rounded-full p-1 hover:bg-red-700 transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="text-left bg-gray-50 dark:bg-gray-700 rounded-lg p-3 space-y-1">
                    <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="fileName"></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="'Size: ' + formatFileSize(fileSize)"></p>
                </div>

                <button 
                    @click="uploadFile()"
                    :disabled="uploading"
                    type="button"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition font-medium"
                >
                    <template x-if="!uploading">
                        <span>Upload Image</span>
                    </template>
                    <template x-if="uploading">
                        <span>Uploading...</span>
                    </template>
                </button>
            </div>
        </template>

        {{-- Upload Progress --}}
        <template x-if="uploading">
            <div class="space-y-4">
                <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="fileName"></p>

                <div class="space-y-2">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Uploading...</span>
                        <span class="text-xs font-medium text-gray-600 dark:text-gray-400" x-text="progress + '%'"></span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                        <div 
                            :style="{width: progress + '%'}"
                            class="bg-blue-600 h-full transition-all duration-200"
                        ></div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Upload Complete --}}
        <template x-if="uploadComplete && !uploading">
            <div class="space-y-3">
                <svg class="w-12 h-12 mx-auto text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>

                <div>
                    <p class="text-sm font-medium text-green-600 dark:text-green-400">Upload complete!</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="uploadedUrl"></p>
                </div>

                <div class="flex gap-2 justify-center pt-2">
                    <button 
                        @click="reset()"
                        type="button"
                        class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                    >
                        Upload Another
                    </button>
                    <button 
                        @click="copyToClipboard()"
                        type="button"
                        class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                    >
                        Copy URL
                    </button>
                </div>
            </div>
        </template>

        {{-- Error Message --}}
        <template x-if="error">
            <div class="space-y-3">
                <svg class="w-12 h-12 mx-auto text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>

                <div>
                    <p class="text-sm font-medium text-red-600 dark:text-red-400">Upload failed</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="error"></p>
                </div>

                <button 
                    @click="reset()"
                    type="button"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium"
                >
                    Try Again
                </button>
            </div>
        </template>
    </div>

    {{-- Hidden Input for Form Submission --}}
    <template x-if="uploadedUrl">
        <input 
            type="hidden"
            :name="name"
            :value="uploadedUrl"
        >
    </template>
</div>

<script>
function imageUpload(name, action) {
    return {
        name: name,
        action: action,
        
        // State
        file: null,
        preview: null,
        fileName: '',
        fileSize: 0,
        isDragging: false,
        uploading: false,
        progress: 0,
        uploadComplete: false,
        uploadedUrl: '',
        error: null,

        /**
         * Handle file selection from input
         */
        handleFileSelect(e) {
            const files = e.target.files;
            if (files.length > 0) {
                this.processFile(files[0]);
            }
        },

        /**
         * Handle drag and drop
         */
        handleDrop(e) {
            this.isDragging = false;
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.processFile(files[0]);
            }
        },

        /**
         * Process selected file
         */
        processFile(file) {
            // Validate
            if (!file.type.startsWith('image/')) {
                this.error = 'Please select a valid image file';
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                this.error = 'File size must be less than 5MB';
                return;
            }

            this.file = file;
            this.fileName = file.name;
            this.fileSize = file.size;
            this.error = null;

            // Generate preview
            const reader = new FileReader();
            reader.onload = (e) => {
                this.preview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        /**
         * Clear preview
         */
        clearPreview() {
            this.file = null;
            this.preview = null;
            this.fileName = '';
            this.fileSize = 0;
            this.$refs.fileInput.value = '';
        },

        /**
         * Upload file
         */
        async uploadFile() {
            if (!this.file) return;

            this.uploading = true;
            this.progress = 0;
            this.error = null;

            const formData = new FormData();
            formData.append('file', this.file);

            try {
                const xhr = new XMLHttpRequest();

                // Track progress
                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        this.progress = Math.round((e.loaded / e.total) * 100);
                    }
                });

                // Handle completion
                xhr.addEventListener('load', () => {
                    if (xhr.status === 200 || xhr.status === 201) {
                        const response = JSON.parse(xhr.responseText);
                        this.uploadedUrl = response.url || response.path;
                        this.uploadComplete = true;
                        this.$dispatch('image-uploaded', { url: this.uploadedUrl });
                    } else {
                        const response = JSON.parse(xhr.responseText);
                        this.error = response.message || 'Upload failed';
                    }
                });

                // Handle error
                xhr.addEventListener('error', () => {
                    this.error = 'Network error during upload';
                });

                xhr.open('POST', this.action);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.send(formData);
            } catch (err) {
                this.error = err.message || 'Upload failed';
            } finally {
                this.uploading = false;
            }
        },

        /**
         * Reset component
         */
        reset() {
            this.file = null;
            this.preview = null;
            this.fileName = '';
            this.fileSize = 0;
            this.progress = 0;
            this.uploadComplete = false;
            this.uploadedUrl = '';
            this.error = null;
            this.$refs.fileInput.value = '';
        },

        /**
         * Format file size
         */
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        },

        /**
         * Copy URL to clipboard
         */
        copyToClipboard() {
            navigator.clipboard.writeText(this.uploadedUrl).then(() => {
                this.$dispatch('image-copied');
            });
        },
    };
}
</script>
