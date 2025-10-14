<?php

use App\Modules\Media\Models\Media;
use App\Modules\User\Models\User;

describe('Media Model', function () {
    
    test('has correct fillable attributes', function () {
        $media = new Media();
        
        $fillable = $media->getFillable();
        
        expect($fillable)->toContain('filename', 'path', 'mime_type', 'size', 'alt_text');
    });
    
    test('belongs to user', function () {
        $user = User::factory()->create();
        $media = Media::factory()->create(['user_id' => $user->id]);
        
        expect($media->user)->toBeInstanceOf(User::class);
        expect($media->user->id)->toBe($user->id);
    });
    
    test('images scope returns only image files', function () {
        Media::factory()->count(3)->create(['mime_type' => 'image/jpeg']);
        Media::factory()->count(2)->create(['mime_type' => 'application/pdf']);
        
        $images = Media::images()->get();
        
        expect($images->count())->toBeGreaterThanOrEqual(3);
        expect($images->every(fn($media) => str_starts_with($media->mime_type, 'image/')))->toBeTrue();
    });
    
    test('documents scope returns only document files', function () {
        Media::factory()->count(2)->create(['mime_type' => 'application/pdf']);
        Media::factory()->count(3)->create(['mime_type' => 'image/jpeg']);
        
        $documents = Media::documents()->get();
        
        expect($documents->count())->toBeGreaterThanOrEqual(2);
    });
    
    test('is_image accessor returns correct boolean', function () {
        $image = Media::factory()->create(['mime_type' => 'image/jpeg']);
        $document = Media::factory()->create(['mime_type' => 'application/pdf']);
        
        expect($image->is_image)->toBeTrue();
        expect($document->is_image)->toBeFalse();
    });
    
    test('is_document accessor returns correct boolean', function () {
        $document = Media::factory()->create(['mime_type' => 'application/pdf']);
        $image = Media::factory()->create(['mime_type' => 'image/jpeg']);
        
        expect($document->is_document)->toBeTrue();
        expect($image->is_document)->toBeFalse();
    });
    
    test('human_readable_size accessor formats size correctly', function () {
        $media = Media::factory()->create(['size' => 1024]); // 1KB
        
        expect($media->human_readable_size)->toContain('KB');
    });
    
    test('url accessor returns correct path', function () {
        $media = Media::factory()->create(['path' => 'media/test.jpg']);
        
        expect($media->url)->toContain('test.jpg');
    });
    
    test('search scope finds media by filename', function () {
        Media::factory()->create(['filename' => 'test-image.jpg']);
        Media::factory()->create(['filename' => 'document.pdf']);
        
        $results = Media::search('image')->get();
        
        expect($results->count())->toBeGreaterThanOrEqual(1);
    });
    
    test('getTotalSize returns sum of all media sizes', function () {
        Media::factory()->count(3)->create(['size' => 1024]);
        
        $totalSize = Media::getTotalSize();
        
        expect($totalSize)->toBeGreaterThanOrEqual(3072); // 3 * 1024
    });
});
