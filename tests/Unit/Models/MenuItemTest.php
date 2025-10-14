<?php

use App\Modules\Menu\Models\Menu;
use App\Modules\Menu\Models\MenuItem;

test('MenuItem → creates menu item with title and url', function () {
    $menu = Menu::factory()->create();
    $item = MenuItem::factory()->create([
        'menu_id' => $menu->id,
        'title' => 'Home',
        'url' => '/home',
    ]);

    expect($item->title)->toBe('Home')
        ->and($item->url)->toBe('/home')
        ->and($item->menu_id)->toBe($menu->id);
});

test('MenuItem → belongs to menu', function () {
    $menu = Menu::factory()->create();
    $item = MenuItem::factory()->create(['menu_id' => $menu->id]);

    expect($item->menu)->not->toBeNull()
        ->and($item->menu->id)->toBe($menu->id);
});

test('MenuItem → can have parent item', function () {
    $menu = Menu::factory()->create();
    $parent = MenuItem::factory()->create(['menu_id' => $menu->id, 'parent_id' => null]);
    $child = MenuItem::factory()->create(['menu_id' => $menu->id, 'parent_id' => $parent->id]);

    expect($child->parent)->not->toBeNull()
        ->and($child->parent->id)->toBe($parent->id);
});

test('MenuItem → can have many children', function () {
    $menu = Menu::factory()->create();
    $parent = MenuItem::factory()->create(['menu_id' => $menu->id, 'parent_id' => null]);
    MenuItem::factory()->count(3)->create(['menu_id' => $menu->id, 'parent_id' => $parent->id]);

    $children = $parent->children()->get();
    expect($children)->toHaveCount(3);
});

test('MenuItem → children are ordered by order column', function () {
    $menu = Menu::factory()->create();
    $parent = MenuItem::factory()->create(['menu_id' => $menu->id]);
    $child1 = MenuItem::factory()->create(['menu_id' => $menu->id, 'parent_id' => $parent->id, 'order' => 2]);
    $child2 = MenuItem::factory()->create(['menu_id' => $menu->id, 'parent_id' => $parent->id, 'order' => 1]);

    $children = $parent->children()->get();
    expect($children->first()->id)->toBe($child2->id)
        ->and($children->last()->id)->toBe($child1->id);
});

test('MenuItem → can be active or inactive', function () {
    $menu = Menu::factory()->create();
    $activeItem = MenuItem::factory()->create(['menu_id' => $menu->id, 'is_active' => true]);
    $inactiveItem = MenuItem::factory()->inactive()->create(['menu_id' => $menu->id]);

    expect($activeItem->is_active)->toBeTrue()
        ->and($inactiveItem->is_active)->toBeFalse();
});

test('MenuItem → has different types', function () {
    $menu = Menu::factory()->create();
    $linkItem = MenuItem::factory()->create(['menu_id' => $menu->id, 'type' => 'link']);
    $pageItem = MenuItem::factory()->create(['menu_id' => $menu->id, 'type' => 'page']);

    expect($linkItem->type)->toBe('link')
        ->and($pageItem->type)->toBe('page');
});

test('MenuItem → can have icon and css class', function () {
    $menu = Menu::factory()->create();
    $item = MenuItem::factory()->create([
        'menu_id' => $menu->id,
        'icon' => 'home',
        'css_class' => 'nav-item',
    ]);

    expect($item->icon)->toBe('home')
        ->and($item->css_class)->toBe('nav-item');
});

test('MenuItem → factory creates valid item', function () {
    $item = MenuItem::factory()->create();

    expect($item->title)->toBeString()
        ->and($item->url)->toBeString()
        ->and($item->order)->toBeInt()
        ->and($item->is_active)->toBeBool();
});

test('MenuItem → updates timestamps', function () {
    $menu = Menu::factory()->create();
    $item = MenuItem::factory()->create(['menu_id' => $menu->id]);
    $originalUpdatedAt = $item->updated_at;

    sleep(1);
    $item->update(['title' => 'Updated Title']);

    expect($item->updated_at->isAfter($originalUpdatedAt))->toBeTrue();
});
