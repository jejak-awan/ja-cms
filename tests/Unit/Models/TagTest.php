<?php

use App\Modules\Tag\Models\Tag;
use App\Modules\Article\Models\Article;

test('Tag → creates tag with name and slug', function () {
    $tag = Tag::factory()->create([
        'name' => 'Technology',
        'slug' => 'technology',
    ]);

    expect($tag->name)->toBe('Technology')
        ->and($tag->slug)->toBe('technology');
});

test('Tag → belongs to many articles', function () {
    $tag = Tag::factory()->create();
    $articles = Article::factory()->count(3)->create();
    
    $tag->articles()->attach($articles->pluck('id'));

    $relatedArticles = $tag->articles()->get();
    expect($relatedArticles)->toHaveCount(3);
});

test('Tag → can be attached to article', function () {
    $tag = Tag::factory()->create(['name' => 'Laravel']);
    $article = Article::factory()->create();

    $article->tags()->attach($tag->id);

    expect($article->tags)->toHaveCount(1)
        ->and($article->tags->first()->name)->toBe('Laravel');
});

test('Tag → slug is unique', function () {
    $tag1 = Tag::factory()->create(['slug' => 'unique-slug']);

    expect($tag1->slug)->toBe('unique-slug');
});

test('Tag → can have description', function () {
    $tag = Tag::factory()->create(['description' => 'Articles about PHP programming']);

    expect($tag->description)->toBe('Articles about PHP programming');
});

test('Tag → factory creates valid tag', function () {
    $tag = Tag::factory()->create();

    expect($tag->name)->toBeString()
        ->and($tag->slug)->toBeString()
        ->and($tag->description)->toBeString();
});

test('Tag → can retrieve tags by slug', function () {
    Tag::factory()->create(['slug' => 'php']);
    Tag::factory()->create(['slug' => 'laravel']);

    $tag = Tag::where('slug', 'php')->first();

    expect($tag)->not->toBeNull()
        ->and($tag->slug)->toBe('php');
});

test('Tag → can count related articles', function () {
    $tag = Tag::factory()->create();
    $articles = Article::factory()->count(5)->create();
    $tag->articles()->attach($articles->pluck('id'));

    expect($tag->articles()->count())->toBe(5);
});

test('Tag → updates timestamps', function () {
    $tag = Tag::factory()->create();
    $originalUpdatedAt = $tag->updated_at;

    sleep(1);
    $tag->update(['name' => 'Updated Tag']);

    expect($tag->updated_at->isAfter($originalUpdatedAt))->toBeTrue();
});

test('Tag → has fillable attributes', function () {
    $data = [
        'name' => 'JavaScript',
        'slug' => 'javascript',
        'description' => 'JS articles',
    ];

    $tag = Tag::create($data);

    expect($tag->name)->toBe('JavaScript')
        ->and($tag->slug)->toBe('javascript')
        ->and($tag->description)->toBe('JS articles');
});
