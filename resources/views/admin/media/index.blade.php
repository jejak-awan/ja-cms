@extends('admin.layouts.admin')

@section('title', 'Media Library')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Media Library</h2>
            <p class="text-sm text-gray-600 mt-1">Manage your files and images</p>
        </div>
        <button onclick="openUploadModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            Upload Files
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wide">Total Files</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['total'] }}</h3>
                    <p class="text-blue-100 text-xs mt-1">All media files</p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-xs font-medium uppercase tracking-wide">Media Types</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['images'] + $stats['videos'] + $stats['documents'] }}</h3>
                    <p class="text-green-100 text-xs mt-1">{{ $stats['images'] }} images · {{ $stats['videos'] }} videos · {{ $stats['documents'] }} docs</p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-xs font-medium uppercase tracking-wide">Storage Used</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['size'] }} MB</h3>
                    <p class="text-purple-100 text-xs mt-1">Total disk usage</p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm">
        <!-- Filters -->
        <div class="p-6 border-b border-gray-200">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" placeholder="Search files..." value="{{ request('search') }}" class="px-4 py-2 border rounded-lg">
                <select name="type" class="px-4 py-2 border rounded-lg">
                    <option value="">All Types</option>
                    <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Videos</option>
                    <option value="application" {{ request('type') == 'application' ? 'selected' : '' }}>Documents</option>
                </select>
                <select name="folder" class="px-4 py-2 border rounded-lg">
                    <option value="">All Folders</option>
                    @foreach($folders as $folder)
                        <option value="{{ $folder }}" {{ request('folder') == $folder ? 'selected' : '' }}>{{ $folder }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">Filter</button>
                    <button type="button" onclick="toggleBulkMode()" id="bulkBtn" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">Select</button>
                </div>
            </form>
        </div>

        <!-- Bulk Actions Bar -->
        <div id="bulkActions" class="hidden p-4 bg-blue-50 border-b border-blue-200">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-blue-900"><span id="selectedCount">0</span> file(s) selected</span>
                <div class="flex gap-2">
                    <button onclick="bulkDelete()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm">Delete Selected</button>
                    <button onclick="toggleBulkMode()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm">Cancel</button>
                </div>
            </div>
        </div>

        <!-- Media Grid -->
        <div class="p-6">
            @if($media->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4" id="mediaGrid">
                @foreach($media as $item)
                <div class="group relative bg-gray-50 rounded-lg overflow-hidden hover:shadow-lg transition cursor-pointer media-item" data-id="{{ $item->id }}">
                    <div class="bulk-checkbox hidden absolute top-2 left-2 z-10">
                        <input type="checkbox" class="w-5 h-5 rounded" value="{{ $item->id }}" onchange="updateBulkSelection()">
                    </div>
                    
                    <div class="aspect-square flex items-center justify-center bg-gray-100" onclick="showMediaDetails({{ $item->id }})">
                        @if(str_starts_with($item->mime_type, 'image/'))
                            <img src="{{ Storage::url($item->path) }}" alt="{{ $item->alt_text }}" class="w-full h-full object-cover">
                        @elseif(str_starts_with($item->mime_type, 'video/'))
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @else
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        @endif
                    </div>
                    
                    <div class="p-2">
                        <p class="text-xs font-medium text-gray-900 truncate">{{ $item->original_filename }}</p>
                        <p class="text-xs text-gray-500">{{ number_format($item->size / 1024, 1) }} KB</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-600">No media files found</p>
                <button onclick="document.getElementById('fileInput').click()" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Upload Files</button>
            </div>
            @endif
        </div>

        @if($media->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $media->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Upload Files</h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="p-6">
            <!-- Dropzone -->
            <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-12 text-center bg-gray-50 hover:border-blue-400 hover:bg-blue-50 transition cursor-pointer">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p class="mt-4 text-sm text-gray-600">
                    <span class="font-semibold text-blue-600">Click to upload</span> or drag and drop
                </p>
                <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF, MP4, PDF up to 10MB</p>
            </div>
            <input type="file" id="fileInput" multiple class="hidden">

            <!-- Folder Selection -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload to folder (optional)</label>
                <input type="text" id="uploadFolder" placeholder="e.g., products, blog, uploads" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Upload Progress -->
            <div id="uploadProgress" class="hidden mt-4">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-gray-600">Uploading files...</span>
                    <span class="text-gray-600" id="progressText">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all" style="width: 0%"></div>
                </div>
            </div>

            <!-- File Preview List -->
            <div id="fileList" class="mt-4 space-y-2 max-h-48 overflow-y-auto"></div>
        </div>
        <div class="p-6 border-t border-gray-200 flex justify-end gap-2">
            <button onclick="closeUploadModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
            <button onclick="startUpload()" id="uploadBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg" disabled>Upload</button>
        </div>
    </div>
</div>

<!-- Media Details Modal -->
<div id="mediaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Media Details</h3>
                <button onclick="closeMediaModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modalContent"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let bulkMode = false;
let selectedIds = [];
let selectedFiles = [];

function openUploadModal() {
    document.getElementById('uploadModal').classList.remove('hidden');
    selectedFiles = [];
    document.getElementById('fileList').innerHTML = '';
    document.getElementById('uploadBtn').disabled = true;
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.getElementById('uploadProgress').classList.add('hidden');
    document.getElementById('progressBar').style.width = '0%';
    selectedFiles = [];
}

// Drag & Drop Setup
document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    
    dropzone.addEventListener('click', () => fileInput.click());
    
    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => {
            dropzone.classList.add('border-blue-500', 'bg-blue-100');
        });
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => {
            dropzone.classList.remove('border-blue-500', 'bg-blue-100');
        });
    });
    
    dropzone.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        handleFiles(files);
    });
});

function handleFiles(files) {
    selectedFiles = Array.from(files);
    displayFileList();
    document.getElementById('uploadBtn').disabled = selectedFiles.length === 0;
}

function displayFileList() {
    const fileList = document.getElementById('fileList');
    fileList.innerHTML = '';
    
    selectedFiles.forEach((file, index) => {
        const fileSize = (file.size / 1024).toFixed(1);
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
        fileItem.innerHTML = `
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-900">${file.name}</p>
                    <p class="text-xs text-gray-500">${fileSize} KB</p>
                </div>
            </div>
            <button onclick="removeFile(${index})" class="text-red-500 hover:text-red-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        fileList.appendChild(fileItem);
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    displayFileList();
    document.getElementById('uploadBtn').disabled = selectedFiles.length === 0;
}

function startUpload() {
    if (selectedFiles.length === 0) return;
    
    const formData = new FormData();
    selectedFiles.forEach(file => {
        formData.append('files[]', file);
    });
    
    const folder = document.getElementById('uploadFolder').value;
    if (folder) {
        formData.append('folder', folder);
    }
    
    document.getElementById('uploadProgress').classList.remove('hidden');
    document.getElementById('uploadBtn').disabled = true;
    
    fetch('{{ route("admin.media.upload") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('progressBar').style.width = '100%';
            document.getElementById('progressText').textContent = '100%';
            setTimeout(() => {
                location.reload();
            }, 500);
        }
    })
    .catch(error => {
        alert('Upload failed: ' + error.message);
        document.getElementById('uploadProgress').classList.add('hidden');
        document.getElementById('uploadBtn').disabled = false;
    });
}

function uploadFiles(files) {
    if (!files.length) return;
    
    const formData = new FormData();
    for (let file of files) {
        formData.append('files[]', file);
    }
    
    fetch('{{ route("admin.media.upload") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function toggleBulkMode() {
    bulkMode = !bulkMode;
    document.querySelectorAll('.bulk-checkbox').forEach(el => {
        el.classList.toggle('hidden', !bulkMode);
    });
    document.getElementById('bulkActions').classList.toggle('hidden', !bulkMode);
    document.getElementById('bulkBtn').textContent = bulkMode ? 'Cancel' : 'Select';
    selectedIds = [];
    updateBulkSelection();
}

function updateBulkSelection() {
    selectedIds = Array.from(document.querySelectorAll('.bulk-checkbox input:checked')).map(el => el.value);
    document.getElementById('selectedCount').textContent = selectedIds.length;
}

function bulkDelete() {
    if (!selectedIds.length || !confirm('Delete ' + selectedIds.length + ' file(s)?')) return;
    
    fetch('{{ route("admin.media.bulk-delete") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: selectedIds })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function showMediaDetails(id) {
    if (bulkMode) return;
    
    const media = @json($media->items());
    const item = media.find(m => m.id == id);
    
    if (!item) return;
    
    let preview = '';
    if (item.mime_type.startsWith('image/')) {
        preview = `<img src="${item.path.startsWith('http') ? item.path : '/storage/' + item.path}" class="max-h-96 mx-auto rounded">`;
    } else {
        preview = `<div class="bg-gray-100 p-8 rounded text-center"><p class="text-gray-600">${item.original_filename}</p></div>`;
    }
    
    document.getElementById('modalContent').innerHTML = `
        <div class="space-y-4">
            ${preview}
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="font-medium">Filename:</span> ${item.original_filename}</div>
                <div><span class="font-medium">Size:</span> ${(item.size / 1024).toFixed(1)} KB</div>
                <div><span class="font-medium">Type:</span> ${item.mime_type}</div>
                <div><span class="font-medium">Uploaded:</span> ${new Date(item.created_at).toLocaleDateString()}</div>
            </div>
            <div class="flex gap-2 pt-4">
                <a href="${item.path.startsWith('http') ? item.path : '/storage/' + item.path}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">View Full</a>
                <button onclick="deleteMedia(${item.id})" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm">Delete</button>
            </div>
        </div>
    `;
    
    document.getElementById('mediaModal').classList.remove('hidden');
}

function closeMediaModal() {
    document.getElementById('mediaModal').classList.add('hidden');
}

function deleteMedia(id) {
    if (!confirm('Delete this file?')) return;
    
    fetch(`/admin/media/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
@endsection
