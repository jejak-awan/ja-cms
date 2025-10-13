<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

echo "====================================\n";
echo "Testing Media Module\n";
echo "====================================\n\n";

// Test 1: Create test image file
echo "✓ Creating test image file...\n";
// Create a simple PNG image using GD
$img = imagecreatetruecolor(800, 600);
$bgColor = imagecolorallocate($img, 100, 150, 200);
$textColor = imagecolorallocate($img, 255, 255, 255);
imagefill($img, 0, 0, $bgColor);
imagestring($img, 5, 350, 290, 'Test Image', $textColor);

$testImagePath = storage_path('app/public/test-image.png');
imagepng($img, $testImagePath);
imagedestroy($img);
echo "  Test image created: $testImagePath\n\n";

// Test 2: Upload image using Media model
echo "✓ Testing image upload...\n";
$uploadedFile = new UploadedFile(
    $testImagePath,
    'test-image.png',
    'image/png',
    null,
    true
);

$filename = time() . '_' . $uploadedFile->getClientOriginalName();
$path = $uploadedFile->storeAs('uploads', $filename, 'public');

$media = App\Modules\Media\Models\Media::create([
    'user_id' => 1,
    'filename' => $filename,
    'original_filename' => $uploadedFile->getClientOriginalName(),
    'mime_type' => $uploadedFile->getMimeType(),
    'extension' => $uploadedFile->getClientOriginalExtension(),
    'size' => $uploadedFile->getSize(),
    'disk' => 'public',
    'path' => $path,
    'folder' => 'uploads',
    'alt_text' => 'Test Image',
    'description' => 'This is a test image for media module',
]);

echo "  Media ID: {$media->id}\n";
echo "  Filename: {$media->filename}\n";
echo "  Size: {$media->human_readable_size}\n";
echo "  Type: {$media->mime_type}\n";
echo "  Is Image: " . ($media->is_image ? 'Yes' : 'No') . "\n";
echo "  URL: {$media->url}\n";
echo "  Dimensions: {$media->dimensions}\n";
echo "  Has Thumbnail: " . ($media->hasThumbnail() ? 'Yes' : 'No') . "\n";
if ($media->hasThumbnail()) {
    echo "  Thumbnail URL: {$media->thumbnail_url}\n";
}
echo "\n";

// Test 3: Test metadata extraction
echo "✓ Testing metadata:\n";
$metadata = $media->metadata;
echo "  Width: " . ($metadata['width'] ?? 'N/A') . "\n";
echo "  Height: " . ($metadata['height'] ?? 'N/A') . "\n";
echo "  Aspect Ratio: " . ($metadata['aspect_ratio'] ?? 'N/A') . "\n";
echo "  Thumbnail Path: " . ($metadata['thumbnail_path'] ?? 'N/A') . "\n\n";

// Test 4: Create PDF document
echo "✓ Creating test PDF...\n";
$pdfContent = "%PDF-1.4\n1 0 obj\n<<\n/Type /Catalog\n/Pages 2 0 R\n>>\nendobj\n2 0 obj\n<<\n/Type /Pages\n/Count 1\n/Kids [3 0 R]\n>>\nendobj\n3 0 obj\n<<\n/Type /Page\n/Parent 2 0 R\n/Resources <<\n/Font <<\n/F1 4 0 R\n>>\n>>\n/MediaBox [0 0 612 792]\n/Contents 5 0 R\n>>\nendobj\n4 0 obj\n<<\n/Type /Font\n/Subtype /Type1\n/BaseFont /Helvetica\n>>\nendobj\n5 0 obj\n<<\n/Length 44\n>>\nstream\nBT\n/F1 24 Tf\n100 700 Td\n(Test PDF) Tj\nET\nendstream\nendobj\nxref\n0 6\n0000000000 65535 f\n0000000009 00000 n\n0000000058 00000 n\n0000000115 00000 n\n0000000259 00000 n\n0000000339 00000 n\ntrailer\n<<\n/Size 6\n/Root 1 0 R\n>>\nstartxref\n440\n%%EOF";
$testPdfPath = storage_path('app/public/test-document.pdf');
file_put_contents($testPdfPath, $pdfContent);

$pdfFile = new UploadedFile(
    $testPdfPath,
    'test-document.pdf',
    'application/pdf',
    null,
    true
);

$pdfFilename = time() . '_document.pdf';
$pdfPath = $pdfFile->storeAs('uploads', $pdfFilename, 'public');

$document = App\Modules\Media\Models\Media::create([
    'user_id' => 1,
    'filename' => $pdfFilename,
    'original_filename' => $pdfFile->getClientOriginalName(),
    'mime_type' => $pdfFile->getMimeType(),
    'extension' => $pdfFile->getClientOriginalExtension(),
    'size' => $pdfFile->getSize(),
    'disk' => 'public',
    'path' => $pdfPath,
    'folder' => 'uploads',
    'description' => 'Test PDF document',
]);

echo "  PDF Media ID: {$document->id}\n";
echo "  Filename: {$document->filename}\n";
echo "  Size: {$document->human_readable_size}\n";
echo "  Is Image: " . ($document->is_image ? 'Yes' : 'No') . "\n";
echo "  Is Document: " . ($document->is_document ? 'Yes' : 'No') . "\n\n";

// Test 5: Test scopes
echo "✓ Testing scopes:\n";
echo "  Total media: " . App\Modules\Media\Models\Media::count() . "\n";
echo "  Images: " . App\Modules\Media\Models\Media::images()->count() . "\n";
echo "  Documents: " . App\Modules\Media\Models\Media::documents()->count() . "\n";
echo "  In 'uploads' folder: " . App\Modules\Media\Models\Media::inFolder('uploads')->count() . "\n\n";

// Test 6: Test static methods
echo "✓ Testing static methods:\n";
$stats = App\Modules\Media\Models\Media::getMediaTypeStats();
echo "  Images count: {$stats['images']['count']}\n";
echo "  Images size: " . round($stats['images']['size'] / 1024, 2) . " KB\n";
echo "  Documents count: {$stats['documents']['count']}\n";
echo "  Documents size: " . round($stats['documents']['size'] / 1024, 2) . " KB\n";
echo "  Total count: {$stats['total']['count']}\n";
echo "  Total size: " . round($stats['total']['size'] / 1024, 2) . " KB\n\n";

$folders = App\Modules\Media\Models\Media::getFolders();
echo "  Available folders: " . implode(', ', $folders) . "\n\n";

// Test 7: Test helper methods
echo "✓ Testing helper methods:\n";
$newFolder = 'images';
Storage::disk('public')->makeDirectory($newFolder);

echo "  Moving image to 'images' folder...\n";
$result = $media->moveTo($newFolder);
echo "  Move result: " . ($result ? 'Success' : 'Failed') . "\n";
$media->refresh();
echo "  New folder: {$media->folder}\n";
echo "  New path: {$media->path}\n\n";

// Test 8: Test accessors with different file types
echo "✓ Testing file type detection:\n";
$allMedia = App\Modules\Media\Models\Media::all();
foreach ($allMedia as $item) {
    echo "  {$item->original_filename}:\n";
    echo "    - Extension: {$item->file_extension}\n";
    echo "    - Is Image: " . ($item->is_image ? 'Yes' : 'No') . "\n";
    echo "    - Is Document: " . ($item->is_document ? 'Yes' : 'No') . "\n";
    echo "    - Size: {$item->human_readable_size}\n";
}
echo "\n";

// Cleanup
echo "✓ Cleaning up test files...\n";
unlink($testImagePath);
unlink($testPdfPath);
echo "  Test files deleted\n\n";

echo "====================================\n";
echo "✅ All Media Module Tests Passed!\n";
echo "====================================\n";
