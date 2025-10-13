<?php

namespace App\Modules\Media\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaUploadController extends Controller
{
    /**
     * Handle image upload from TinyMCE editor
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120', // max 5MB
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                // Generate unique filename
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Store in public/storage/uploads/images
                $path = $file->storeAs('uploads/images', $filename, 'public');
                
                // Return URL for TinyMCE
                return response()->json([
                    'location' => Storage::url($path),
                    'success' => true,
                    'message' => 'Image uploaded successfully'
                ]);
            }

            return response()->json([
                'error' => 'No file uploaded'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle multiple file uploads
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadMultiple(Request $request)
    {
        try {
            $request->validate([
                'files.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            ]);

            $uploadedFiles = [];

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploads/images', $filename, 'public');
                    
                    $uploadedFiles[] = [
                        'url' => Storage::url($path),
                        'filename' => $filename,
                        'size' => $file->getSize(),
                        'mime' => $file->getMimeType()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'files' => $uploadedFiles,
                'message' => count($uploadedFiles) . ' file(s) uploaded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete uploaded image
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage(Request $request)
    {
        try {
            $request->validate([
                'path' => 'required|string'
            ]);

            $path = str_replace('/storage/', '', $request->path);
            
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Image deleted successfully'
                ]);
            }

            return response()->json([
                'error' => 'File not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
