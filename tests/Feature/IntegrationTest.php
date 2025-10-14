<?php

use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use App\Modules\User\Models\User;

test('Integration → Article creation flow with category and author', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $article = Article::factory()->create([
        'title' => 'Integration Test Article',
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    expect($article->title)->toBe('Integration Test Article')
        ->and($article->category)->not->toBeNull()
        ->and($article->category->id)->toBe($category->id)
        ->and($article->user)->not->toBeNull()
        ->and($article->user->id)->toBe($user->id);
});

test('Integration → Article with tags relationship', function () {
    $article = Article::factory()->create();
    $tags = \App\Modules\Tag\Models\Tag::factory()->count(3)->create();

    $article->tags()->attach($tags->pluck('id'));

    $article->refresh();
    expect($article->tags)->toHaveCount(3);
});

test('Integration → Category hierarchy with parent-child', function () {
    $parent = Category::factory()->create(['name' => 'Parent Category']);
    $child = Category::factory()->create([
        'name' => 'Child Category',
        'parent_id' => $parent->id,
    ]);

    expect($child->parent)->not->toBeNull()
        ->and($child->parent->id)->toBe($parent->id)
        ->and($parent->children)->toHaveCount(1);
});

test('Integration → Page hierarchy with nested pages', function () {
    $parent = \App\Modules\Page\Models\Page::factory()->create(['title' => 'Parent Page']);
    $child = \App\Modules\Page\Models\Page::factory()->create([
        'title' => 'Child Page',
        'parent_id' => $parent->id,
    ]);

    expect($child->parent)->not->toBeNull()
        ->and($parent->children)->toHaveCount(1);
});

test('Integration → Menu with nested items', function () {
    $menu = \App\Modules\Menu\Models\Menu::factory()->create();
    $parentItem = \App\Modules\Menu\Models\MenuItem::factory()->create([
        'menu_id' => $menu->id,
        'title' => 'Parent Item',
        'parent_id' => null,
    ]);
    $childItem = \App\Modules\Menu\Models\MenuItem::factory()->create([
        'menu_id' => $menu->id,
        'title' => 'Child Item',
        'parent_id' => $parentItem->id,
    ]);

    expect($menu->items)->toHaveCount(1) // Only root level
        ->and($parentItem->children)->toHaveCount(1)
        ->and($childItem->parent->id)->toBe($parentItem->id);
});

test('Integration → SEO attached to article', function () {
    $article = Article::factory()->create();
    $seo = \App\Modules\Seo\Models\Seo::factory()->forModel($article)->create([
        'title' => 'SEO Title',
    ]);

    expect($seo->seoable)->not->toBeNull()
        ->and($seo->seoable->id)->toBe($article->id);
});

test('Integration → Settings grouped retrieval', function () {
    \App\Modules\Setting\Models\Setting::factory()->create([
        'key' => 'smtp_host',
        'value' => 'smtp.example.com',
        'group' => 'email',
        'type' => 'text',
    ]);
    \App\Modules\Setting\Models\Setting::factory()->create([
        'key' => 'smtp_port',
        'value' => '587',
        'group' => 'email',
        'type' => 'integer',
    ]);

    $settings = \App\Modules\Setting\Models\Setting::getGroup('email');

    expect($settings)->toHaveKey('smtp_host')
        ->and($settings)->toHaveKey('smtp_port')
        ->and($settings['smtp_port'])->toBeInt();
});

test('Integration → User with role and permissions', function () {
    $role = \App\Modules\User\Models\Role::factory()->create(['name' => 'editor']);
    $permission = \App\Modules\User\Models\Permission::factory()->create(['name' => 'Edit Articles']);
    
    $role->permissions()->attach($permission->id);

    $permissions = $role->permissions()->get();
    expect($permissions)->toHaveCount(1)
        ->and($permissions->first()->name)->toBe('Edit Articles');
});

test('Integration → Article observer creates slug on save', function () {
    $article = Article::factory()->create(['title' => 'Test Article', 'slug' => null]);

    expect($article->slug)->not->toBeNull()
        ->and($article->slug)->toBe('test-article');
});

test('Integration → Full content publishing workflow', function () {
    // Create all required entities
    $user = User::factory()->create(['role' => 'author']);
    $category = Category::factory()->create();
    $tags = \App\Modules\Tag\Models\Tag::factory()->count(2)->create();
    
    // Create article
    $article = Article::factory()->create([
        'title' => 'Published Article',
        'status' => 'published',
        'user_id' => $user->id,
        'category_id' => $category->id,
        'published_at' => now(),
    ]);
    
    // Attach tags
    $article->tags()->attach($tags->pluck('id'));
    
    // Attach SEO
    $seo = \App\Modules\Seo\Models\Seo::factory()->forModel($article)->create();

    // Verify complete workflow
    expect($article->status)->toBe('published')
        ->and($article->published_at)->not->toBeNull()
        ->and($article->user->id)->toBe($user->id)
        ->and($article->category->id)->toBe($category->id)
        ->and($article->tags)->toHaveCount(2);
});
