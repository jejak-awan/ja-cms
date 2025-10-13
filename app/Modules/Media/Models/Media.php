<?php

namespace App\Modules\Media\Models;

use App\Modules\Media\Observers\MediaObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

#[ObservedBy(MediaObserver::class)]
class Media extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'filename',
        'original_filename',
        'mime_type',
        'extension',
        'size',
        'disk',
        'path',
        'alt_text',
        'description',
        'folder',
        'metadata',
    ];

    protected $casts = [
        'size' => 'integer',
        'metadata' => 'array',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function user()
    {
        return $this->belongsTo(\App\Modules\User\Models\User::class);
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeImages($query)
    {
        return $query->whereIn('mime_type', [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
        ]);
    }

    public function scopeDocuments($query)
    {
        return $query->whereIn('mime_type', [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
        ]);
    }

    public function scopeArchives($query)
    {
        return $query->whereIn('mime_type', [
            'application/zip',
            'application/x-rar-compressed',
            'application/x-7z-compressed',
        ]);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('mime_type', 'LIKE', $type . '%');
    }

    public function scopeInFolder($query, ?string $folder)
    {
        if (is_null($folder)) {
            return $query->whereNull('folder');
        }
        return $query->where('folder', $folder);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->hasThumbnail()) {
            return $this->is_image ? $this->url : null;
        }

        $thumbnailPath = $this->metadata['thumbnail_path'] ?? null;
        
        if ($thumbnailPath && Storage::disk($this->disk)->exists($thumbnailPath)) {
            return Storage::disk($this->disk)->url($thumbnailPath);
        }

        return $this->is_image ? $this->url : null;
    }

    public function getHumanReadableSizeAttribute(): string
    {
        $bytes = $this->size;
        
        if ($bytes < 1024) {
            return $bytes . ' B';
        } elseif ($bytes < 1048576) {
            return round($bytes / 1024, 2) . ' KB';
        } elseif ($bytes < 1073741824) {
            return round($bytes / 1048576, 2) . ' MB';
        } else {
            return round($bytes / 1073741824, 2) . ' GB';
        }
    }

    public function getFileExtensionAttribute(): string
    {
        return $this->extension;
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function getIsDocumentAttribute(): bool
    {
        return in_array($this->mime_type, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
        ]);
    }

    public function getIsArchiveAttribute(): bool
    {
        return in_array($this->mime_type, [
            'application/zip',
            'application/x-rar-compressed',
            'application/x-7z-compressed',
        ]);
    }

    public function getWidthAttribute(): ?int
    {
        return $this->metadata['width'] ?? null;
    }

    public function getHeightAttribute(): ?int
    {
        return $this->metadata['height'] ?? null;
    }

    public function getDimensionsAttribute(): ?string
    {
        if (!$this->is_image || !isset($this->metadata['width'], $this->metadata['height'])) {
            return null;
        }

        return $this->metadata['width'] . ' × ' . $this->metadata['height'];
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    public function hasThumbnail(): bool
    {
        return isset($this->metadata['thumbnail_path']) && 
               Storage::disk($this->disk)->exists($this->metadata['thumbnail_path']);
    }

    public function regenerateThumbnail(): bool
    {
        if (!$this->is_image) {
            return false;
        }

        try {
            // Trigger observer to regenerate
            $this->update(['updated_at' => now()]);
            return true;
        } catch (\Exception $e) {
            logger()->error('Failed to regenerate thumbnail: ' . $e->getMessage());
            return false;
        }
    }

    public function moveTo(string $newFolder): bool
    {
        try {
            $disk = Storage::disk($this->disk);
            $pathInfo = pathinfo($this->path);
            $newPath = $newFolder . '/' . $this->filename;

            // Move file
            if (!$disk->move($this->path, $newPath)) {
                return false;
            }

            // Move thumbnail if exists
            if ($this->hasThumbnail()) {
                $oldThumbPath = $this->metadata['thumbnail_path'];
                $newThumbPath = $newFolder . '/thumbs/' . $this->filename;
                
                if ($disk->exists($oldThumbPath)) {
                    $disk->move($oldThumbPath, $newThumbPath);
                    
                    $metadata = $this->metadata;
                    $metadata['thumbnail_path'] = $newThumbPath;
                    $this->update(['metadata' => $metadata]);
                }
            }

            // Update path and folder
            $this->update([
                'path' => $newPath,
                'folder' => $newFolder,
            ]);

            return true;

        } catch (\Exception $e) {
            logger()->error('Failed to move media: ' . $e->getMessage());
            return false;
        }
    }

    public function download(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return Storage::disk($this->disk)->download($this->path, $this->original_filename);
    }

    // ========================================
    // STATIC METHODS
    // ========================================

    public static function getTotalSize(): int
    {
        return self::sum('size');
    }

    public static function getTotalSizeByType(string $type): int
    {
        return self::byType($type)->sum('size');
    }

    public static function getFolders(): array
    {
        return self::whereNotNull('folder')
            ->distinct()
            ->pluck('folder')
            ->toArray();
    }

    public static function getMediaTypeStats(): array
    {
        return [
            'images' => [
                'count' => self::images()->count(),
                'size' => self::images()->sum('size'),
            ],
            'documents' => [
                'count' => self::documents()->count(),
                'size' => self::documents()->sum('size'),
            ],
            'archives' => [
                'count' => self::archives()->count(),
                'size' => self::archives()->sum('size'),
            ],
            'total' => [
                'count' => self::count(),
                'size' => self::sum('size'),
            ],
        ];
    }
}

