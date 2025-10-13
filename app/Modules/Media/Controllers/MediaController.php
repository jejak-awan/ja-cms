<?php

namespace App\Modules\Media\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Media\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::with('user')->latest();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('mime_type', 'like', $request->type . '%');
        }

        // Filter by folder
        if ($request->filled('folder')) {
            $query->where('folder', $request->folder);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('original_filename', 'like', '%' . $request->search . '%')
                  ->orWhere('alt_text', 'like', '%' . $request->search . '%');
            });
        }

        $media = $query->paginate(24);
        $folders = Media::distinct()->pluck('folder')->filter();

        $stats = [
            'total' => Media::count(),
            'images' => Media::where('mime_type', 'like', 'image/%')->count(),
            'videos' => Media::where('mime_type', 'like', 'video/%')->count(),
            'documents' => Media::whereNotIn('mime_type', function($q) {
                $q->select('mime_type')->from('media')->where('mime_type', 'like', 'image/%')
                  ->orWhere('mime_type', 'like', 'video/%');
            })->count(),
            'size' => round(Media::sum('size') / 1048576, 2) // MB
        ];

        if ($request->wantsJson()) {
            return response()->json($media);
        }

        return view('admin.media.index', compact('media', 'folders', 'stats'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240', // 10MB max
            'folder' => 'nullable|string|max:255'
        ]);

        $uploaded = [];

        foreach ($request->file('files') as $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('media/' . ($request->folder ?? 'uploads'), $filename, 'public');

            $metadata = [];
            if (str_starts_with($file->getMimeType(), 'image/')) {
                $imageSize = getimagesize($file->getRealPath());
                $metadata = [
                    'width' => $imageSize[0] ?? null,
                    'height' => $imageSize[1] ?? null,
                ];
            }

            $media = Media::create([
                'user_id' => auth()->id(),
                'filename' => $filename,
                'original_filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'path' => $path,
                'folder' => $request->folder,
                'metadata' => $metadata,
            ]);

            $uploaded[] = $media;
        }

        return response()->json([
            'success' => true,
            'message' => count($uploaded) . ' file(s) uploaded successfully',
            'media' => $uploaded
        ]);
    }

    public function update(Request $request, Media $media)
    {
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'folder' => 'nullable|string|max:255'
        ]);

        $media->update($request->only(['alt_text', 'description', 'folder']));

        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully',
            'media' => $media
        ]);
    }

    public function destroy(Media $media)
    {
        Storage::disk('public')->delete($media->path);
        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:media,id'
        ]);

        $media = Media::whereIn('id', $request->ids)->get();
        
        foreach ($media as $item) {
            Storage::disk('public')->delete($item->path);
            $item->delete();
        }

        return response()->json([
            'success' => true,
            'message' => count($media) . ' file(s) deleted successfully'
        ]);
    }
}
