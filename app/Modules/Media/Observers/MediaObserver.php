<?php

namespace App\Modules\Media\Observers;

use App\Modules\Media\Models\Media;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaObserver
{
    public function created(Media $media): void
    {
        // Extract and store metadata first
        if (empty($media->metadata)) {
            $metadata = $this->extractMetadata($media);
            
            // Auto-generate thumbnail for images
            if ($media->is_image) {
                $thumbnailPath = $this->generateThumbnail($media);
                if ($thumbnailPath) {
                    $metadata['thumbnail_path'] = $thumbnailPath;
                }
            }
            
            // Update all metadata at once
            $media->updateQuietly(['metadata' => $metadata]);
        }
    }

    public function updating(Media $media): void
    {
        // If file changed, regenerate thumbnail
        if ($media->isDirty('path') && $media->is_image) {
            $originalPath = $media->getOriginal('path');
            if ($originalPath) {
                $this->deleteThumbnail($originalPath);
            }
            $this->generateThumbnail($media);
        }
    }

    public function deleting(Media $media): void
    {
        // Delete physical file and thumbnail
        if (Storage::disk($media->disk)->exists($media->path)) {
            Storage::disk($media->disk)->delete($media->path);
        }

        $this->deleteThumbnail($media->path);
    }

    private function generateThumbnail(Media $media): ?string
    {
        try {
            if (!$media->is_image) return null;

            $disk = Storage::disk($media->disk);
            if (!$disk->exists($media->path)) return null;

            // Create thumbnail path
            $pathInfo = pathinfo($media->path);
            $thumbnailPath = $pathInfo['dirname'] . '/thumbs/' . $pathInfo['basename'];

            // Ensure thumbs directory exists
            $thumbDir = $pathInfo['dirname'] . '/thumbs';
            if (!$disk->exists($thumbDir)) {
                $disk->makeDirectory($thumbDir);
            }

            // Create thumbnail (300x300 max, maintain aspect ratio)
            $manager = new ImageManager(new Driver());
            $image = $manager->read($disk->path($media->path));
            
            $image->scale(width: 300);
            
            // Save thumbnail
            $disk->put($thumbnailPath, (string) $image->encode());

            return $thumbnailPath;

        } catch (\Exception $e) {
            // Log error but don't fail the operation
            logger()->error('Failed to generate thumbnail: ' . $e->getMessage(), [
                'media_id' => $media->id,
                'path' => $media->path
            ]);
            return null;
        }
    }

    private function deleteThumbnail(string $path): void
    {
        $pathInfo = pathinfo($path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbs/' . $pathInfo['basename'];

        if (Storage::exists($thumbnailPath)) {
            Storage::delete($thumbnailPath);
        }
    }

    private function extractMetadata(Media $media): array
    {
        $metadata = [];

        try {
            $disk = Storage::disk($media->disk);
            
            if (!$disk->exists($media->path)) {
                return $metadata;
            }

            // For images, extract dimensions
            if ($media->is_image) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($disk->path($media->path));
                
                $metadata['width'] = $image->width();
                $metadata['height'] = $image->height();
                $metadata['aspect_ratio'] = round($image->width() / $image->height(), 2);
            }

            // Add file stats
            $metadata['last_modified'] = $disk->lastModified($media->path);

        } catch (\Exception $e) {
            logger()->error('Failed to extract metadata: ' . $e->getMessage(), [
                'media_id' => $media->id,
                'path' => $media->path
            ]);
        }

        return $metadata;
    }
}
