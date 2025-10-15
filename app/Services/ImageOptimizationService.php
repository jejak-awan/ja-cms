<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizationService
{
    protected $manager;
    
    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }
    
    /**
     * Optimize and resize image
     */
    public function optimizeImage(string $path, array $options = []): array
    {
        $defaultOptions = [
            'max_width' => 1920,
            'max_height' => 1080,
            'quality' => 85,
            'format' => 'webp',
            'create_thumbnails' => true,
            'thumbnail_sizes' => [
                'small' => [150, 150],
                'medium' => [300, 300],
                'large' => [600, 600],
            ]
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        try {
            $image = $this->manager->read(Storage::path($path));
            
            // Get original dimensions
            $originalWidth = $image->width();
            $originalHeight = $image->height();
            
            // Resize if needed
            if ($originalWidth > $options['max_width'] || $originalHeight > $options['max_height']) {
                $image->scaleDown($options['max_width'], $options['max_height']);
            }
            
            // Generate optimized filename
            $pathInfo = pathinfo($path);
            $optimizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_optimized.' . $options['format'];
            
            // Save optimized image
            $image->toWebp($options['quality'])->save(Storage::path($optimizedPath));
            
            $result = [
                'original_path' => $path,
                'optimized_path' => $optimizedPath,
                'original_size' => Storage::size($path),
                'optimized_size' => Storage::size($optimizedPath),
                'compression_ratio' => $this->calculateCompressionRatio(Storage::size($path), Storage::size($optimizedPath)),
                'thumbnails' => []
            ];
            
            // Create thumbnails if requested
            if ($options['create_thumbnails']) {
                $result['thumbnails'] = $this->createThumbnails($path, $options['thumbnail_sizes']);
            }
            
            return $result;
            
        } catch (\Exception $e) {
            throw new \Exception("Image optimization failed: " . $e->getMessage());
        }
    }
    
    /**
     * Create thumbnails for an image
     */
    protected function createThumbnails(string $originalPath, array $sizes): array
    {
        $thumbnails = [];
        $pathInfo = pathinfo($originalPath);
        
        foreach ($sizes as $name => $dimensions) {
            try {
                $image = $this->manager->read(Storage::path($originalPath));
                $image->cover($dimensions[0], $dimensions[1]);
                
                $thumbnailPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_' . $name . '.webp';
                $image->toWebp(80)->save(Storage::path($thumbnailPath));
                
                $thumbnails[$name] = [
                    'path' => $thumbnailPath,
                    'width' => $dimensions[0],
                    'height' => $dimensions[1],
                    'size' => Storage::size($thumbnailPath)
                ];
                
            } catch (\Exception $e) {
                \Log::warning("Failed to create thumbnail {$name}", ['error' => $e->getMessage()]);
            }
        }
        
        return $thumbnails;
    }
    
    /**
     * Calculate compression ratio
     */
    protected function calculateCompressionRatio(int $originalSize, int $optimizedSize): float
    {
        if ($originalSize == 0) return 0;
        return round((($originalSize - $optimizedSize) / $originalSize) * 100, 2);
    }
    
    /**
     * Batch optimize images
     */
    public function batchOptimize(array $paths, array $options = []): array
    {
        $results = [];
        
        foreach ($paths as $path) {
            try {
                $results[$path] = $this->optimizeImage($path, $options);
            } catch (\Exception $e) {
                $results[$path] = [
                    'error' => $e->getMessage(),
                    'original_path' => $path
                ];
            }
        }
        
        return $results;
    }
    
    /**
     * Get image information
     */
    public function getImageInfo(string $path): array
    {
        try {
            $image = $this->manager->read(Storage::path($path));
            
            return [
                'path' => $path,
                'width' => $image->width(),
                'height' => $image->height(),
                'size' => Storage::size($path),
                'mime_type' => Storage::mimeType($path),
                'format' => $image->origin()->mediaType(),
                'aspect_ratio' => round($image->width() / $image->height(), 2),
            ];
        } catch (\Exception $e) {
            return [
                'path' => $path,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate responsive image sizes
     */
    public function generateResponsiveImages(string $path): array
    {
        $sizes = [
            'xs' => 320,
            'sm' => 640,
            'md' => 768,
            'lg' => 1024,
            'xl' => 1280,
            '2xl' => 1920,
        ];
        
        $responsiveImages = [];
        $pathInfo = pathinfo($path);
        
        foreach ($sizes as $breakpoint => $maxWidth) {
            try {
                $image = $this->manager->read(Storage::path($path));
                
                // Only resize if original is larger
                if ($image->width() > $maxWidth) {
                    $image->scaleDown($maxWidth);
                }
                
                $responsivePath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_' . $breakpoint . '.webp';
                $image->toWebp(85)->save(Storage::path($responsivePath));
                
                $responsiveImages[$breakpoint] = [
                    'path' => $responsivePath,
                    'width' => $image->width(),
                    'size' => Storage::size($responsivePath)
                ];
                
            } catch (\Exception $e) {
                \Log::warning("Failed to create responsive image for {$breakpoint}", ['error' => $e->getMessage()]);
            }
        }
        
        return $responsiveImages;
    }
}
