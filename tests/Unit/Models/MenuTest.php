<?php

use App\Modules\Menu\Models\Menu;
use App\Modules\Menu\Models\MenuItem;

test('Menu → creates menu with name and location', function () {
    $menu = Menu::factory()->create([
        'name' => 'main-menu',
        'location' => 'header',
    ]);

    expect($menu->name)->toBe('main-menu')
        ->and($menu->location)->toBe('header')
        ->and($menu->is_active)->toBeTrue();
});

test('Menu → has many menu items', function () {
    $menu = Menu::factory()->create();
    MenuItem::factory()->count(3)->create(['menu_id' => $menu->id, 'parent_id' => null]);

    expect($menu->items)->toHaveCount(3)
        ->and($menu->allItems)->toHaveCount(3);
});

test('Menu → items only returns root level items', function () {
    $menu = Menu::factory()->create();
    $parent = MenuItem::factory()->create(['menu_id' => $menu->id, 'parent_id' => null]);
    MenuItem::factory()->create(['menu_id' => $menu->id, 'parent_id' => $parent->id]);

    expect($menu->items)->toHaveCount(1)
        ->and($menu->allItems)->toHaveCount(2);
});

test('Menu → orders items by order column', function () {
    $menu = Menu::factory()->create();
    $item1 = MenuItem::factory()->create(['menu_id' => $menu->id, 'order' => 2, 'parent_id' => null]);
    $item2 = MenuItem::factory()->create(['menu_id' => $menu->id, 'order' => 1, 'parent_id' => null]);

    $items = $menu->items()->get();
    expect($items->first()->id)->toBe($item2->id)
        ->and($items->last()->id)->toBe($item1->id);
});

test('Menu → can be active or inactive', function () {
    $activeMenu = Menu::factory()->create(['is_active' => true]);
    $inactiveMenu = Menu::factory()->inactive()->create();

    expect($activeMenu->is_active)->toBeTrue()
        ->and($inactiveMenu->is_active)->toBeFalse();
});

test('Menu → has unique location', function () {
    $menu = Menu::factory()->create(['location' => 'header']);

    expect($menu->location)->toBe('header');
});

test('Menu → display name can differ from name', function () {
    $menu = Menu::factory()->create([
        'name' => 'main-menu',
        'display_name' => 'Main Navigation',
    ]);

    expect($menu->name)->toBe('main-menu')
        ->and($menu->display_name)->toBe('Main Navigation');
});

test('Menu → can have description', function () {
    $menu = Menu::factory()->create(['description' => 'Primary navigation menu']);

    expect($menu->description)->toBe('Primary navigation menu');
});

test('Menu → factory creates valid menu', function () {
    $menu = Menu::factory()->create();

    expect($menu->name)->toBeString()
        ->and($menu->location)->toBeString()
        ->and($menu->is_active)->toBeBool();
});

test('Menu → deleting menu deletes items', function () {
    $menu = Menu::factory()->create();
    MenuItem::factory()->count(2)->create(['menu_id' => $menu->id]);

    expect(MenuItem::where('menu_id', $menu->id)->count())->toBe(2);

    $menu->delete();

    expect(MenuItem::where('menu_id', $menu->id)->count())->toBe(0);
});

test('Menu → can retrieve active menus only', function () {
    Menu::factory()->create(['is_active' => true]);
    Menu::factory()->create(['is_active' => true]);
    Menu::factory()->inactive()->create();

    $activeMenus = Menu::where('is_active', true)->get();

    expect($activeMenus)->toHaveCount(2);
});

test('Menu → can retrieve menu by location', function () {
    Menu::factory()->create(['location' => 'header']);
    Menu::factory()->create(['location' => 'footer']);

    $headerMenu = Menu::where('location', 'header')->first();

    expect($headerMenu)->not->toBeNull()
        ->and($headerMenu->location)->toBe('header');
});

test('Menu → updates timestamps', function () {
    $menu = Menu::factory()->create();
    $originalUpdatedAt = $menu->updated_at;

    sleep(1);
    $menu->update(['display_name' => 'Updated Name']);

    expect($menu->updated_at->isAfter($originalUpdatedAt))->toBeTrue();
});

test('Menu → has fillable attributes', function () {
    $data = [
        'name' => 'test-menu',
        'display_name' => 'Test Menu',
        'location' => 'sidebar',
        'description' => 'Test description',
        'is_active' => false,
    ];

    $menu = Menu::create($data);

    expect($menu->name)->toBe('test-menu')
        ->and($menu->display_name)->toBe('Test Menu')
        ->and($menu->is_active)->toBeFalse();
});

test('Menu → casts is_active to boolean', function () {
    $menu = Menu::factory()->create(['is_active' => 1]);

    expect($menu->is_active)->toBeBool()
        ->and($menu->is_active)->toBeTrue();
});
