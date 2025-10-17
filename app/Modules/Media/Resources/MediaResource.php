<?php

namespace App\Modules\Media\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray($request)
    {
        $fullPath = $this->disk === 's3' 
            ? Storage::disk('s3')->url($this->path)
            : Storage::url($this->path);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'path' => $this->path,
            'full_url' => $fullPath,
            'file_name' => $this->file_name,
            'file_type' => $this->file_type,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'size_formatted' => $this->formatFileSize($this->size),
            'width' => $this->when($this->isImage(), $this->width),
            'height' => $this->when($this->isImage(), $this->height),
            'duration' => $this->when($this->isVideo(), $this->duration),
            'disk' => $this->disk,
            'is_public' => (bool) $this->is_public,
            'alt_text' => $this->alt_text,
            'uploaded_by' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'usage_count' => $this->when(
                auth()->check() && auth()->user()->hasPermission('media.view_usage'),
                $this->usageCount()
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Get additional data that should be returned with the response
     */
    public function with($request)
    {
        return [
            'meta' => [
                'is_image' => $this->isImage(),
                'is_video' => $this->isVideo(),
                'is_audio' => $this->isAudio(),
                'is_document' => $this->isDocument(),
            ],
        ];
    }

    /**
     * Check if media is image
     */
    private function isImage(): bool
    {
        return in_array($this->mime_type, [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
        ]);
    }

    /**
     * Check if media is video
     */
    private function isVideo(): bool
    {
        return in_array($this->mime_type, [
            'video/mp4',
            'video/webm',
            'video/ogg',
            'video/quicktime',
        ]);
    }

    /**
     * Check if media is audio
     */
    private function isAudio(): bool
    {
        return in_array($this->mime_type, [
            'audio/mpeg',
            'audio/wav',
            'audio/ogg',
            'audio/webm',
        ]);
    }

    /**
     * Check if media is document
     */
    private function isDocument(): bool
    {
        return in_array($this->mime_type, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Format file size
     */
    private function formatFileSize($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Get media usage count
     */
    private function usageCount(): int
    {
        // Count where media is used in articles, pages, etc.
        return 0; // Implement based on your relationship models
    }
}
