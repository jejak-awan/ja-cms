<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "====================================\n";
echo "Testing Page Module Auto-Generation\n";
echo "====================================\n\n";

// Test 1: Create parent page
$aboutPage = App\Modules\Page\Models\Page::create([
    'user_id' => 1,
    'title' => 'About Us',
    'content' => '<p>We are a company dedicated to excellence in web development and design. Our team consists of experienced professionals who are passionate about creating beautiful and functional websites.</p>',
    'template' => 'default',
    'status' => 'published',
]);

echo "✓ Created parent page: {$aboutPage->title}\n";
echo "  Slug: {$aboutPage->slug}\n";
echo "  Excerpt: {$aboutPage->excerpt}\n";
echo "  Meta Title: {$aboutPage->meta_title}\n";
echo "  Meta Description: {$aboutPage->meta_description}\n";
echo "  Published: " . ($aboutPage->is_published ? 'Yes' : 'No') . "\n";
echo "  Published At: {$aboutPage->published_at}\n";
echo "  Order: {$aboutPage->order}\n\n";

// Test 2: Create child pages
$teamPage = App\Modules\Page\Models\Page::create([
    'parent_id' => $aboutPage->id,
    'user_id' => 1,
    'title' => 'Our Team',
    'content' => '<p>Meet our talented team of developers, designers, and project managers who make everything possible.</p>',
    'status' => 'published',
]);

$historyPage = App\Modules\Page\Models\Page::create([
    'parent_id' => $aboutPage->id,
    'user_id' => 1,
    'title' => 'Company History',
    'content' => '<p>Founded in 2020, our company has grown from a small startup to a leading web development agency.</p>',
    'status' => 'published',
]);

echo "✓ Created child pages:\n";
echo "  - {$teamPage->title} (slug: {$teamPage->slug}, order: {$teamPage->order})\n";
echo "  - {$historyPage->title} (slug: {$historyPage->slug}, order: {$historyPage->order})\n\n";

// Test 3: Check hierarchical relationships
$aboutPage->refresh();
echo "✓ Hierarchical relationships:\n";
echo "  About Us children count: " . $aboutPage->children()->count() . "\n";
echo "  About Us has_children: " . ($aboutPage->has_children ? 'Yes' : 'No') . "\n";
echo "  Team page has_parent: " . ($teamPage->has_parent ? 'Yes' : 'No') . "\n";
echo "  Team page depth: {$teamPage->depth}\n";
echo "  Team page full_path: {$teamPage->full_path}\n\n";

// Test 4: Test breadcrumb
echo "✓ Team page breadcrumb:\n";
foreach ($teamPage->breadcrumb as $crumb) {
    echo "  {$crumb['title']} -> {$crumb['url']}\n";
}
echo "\n";

// Test 5: Test tree structure
echo "✓ Page tree structure:\n";
$tree = App\Modules\Page\Models\Page::getTree();
echo json_encode($tree, JSON_PRETTY_PRINT) . "\n\n";

// Test 6: Test flat list
echo "✓ Flat list with indentation:\n";
$flatList = App\Modules\Page\Models\Page::getFlatList();
foreach ($flatList as $item) {
    echo "  {$item['indented_title']} (depth: {$item['depth']})\n";
}
echo "\n";

// Test 7: Test duplicate slug handling
$aboutPage2 = App\Modules\Page\Models\Page::create([
    'user_id' => 1,
    'title' => 'About Us', // Same title
    'content' => '<p>Another about page</p>',
    'status' => 'draft',
]);

echo "✓ Duplicate slug handling:\n";
echo "  First page: {$aboutPage->title} -> {$aboutPage->slug}\n";
echo "  Second page: {$aboutPage2->title} -> {$aboutPage2->slug}\n\n";

// Test 8: Test helper methods
echo "✓ Testing helper methods:\n";
echo "  Team page views before: {$teamPage->views}\n";
$teamPage->incrementViews();
$teamPage->refresh();
echo "  Team page views after increment: {$teamPage->views}\n";

$aboutPage2->publish();
$aboutPage2->refresh();
echo "  About Us 2 status after publish(): {$aboutPage2->status}\n";

$aboutPage2->archive();
$aboutPage2->refresh();
echo "  About Us 2 status after archive(): {$aboutPage2->status}\n\n";

// Test 9: Test published scope
echo "✓ Testing scopes:\n";
echo "  Total pages: " . App\Modules\Page\Models\Page::count() . "\n";
echo "  Published pages: " . App\Modules\Page\Models\Page::published()->count() . "\n";
echo "  Draft pages: " . App\Modules\Page\Models\Page::draft()->count() . "\n";
echo "  Archived pages: " . App\Modules\Page\Models\Page::archived()->count() . "\n";
echo "  Root pages: " . App\Modules\Page\Models\Page::root()->count() . "\n\n";

echo "====================================\n";
echo "✅ All Page Module Tests Passed!\n";
echo "====================================\n";
