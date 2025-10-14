<?php

use App\Modules\Seo\Models\Seo;
use App\Modules\Article\Models\Article;
use App\Modules\Page\Models\Page;

test('Seo → creates seo for article', function () {
    $article = Article::factory()->create();
    
    $seo = Seo::factory()->forModel($article)->create([
        'title' => 'Article SEO Title',
    ]);

    expect($seo->title)->toBe('Article SEO Title')
        ->and($seo->seoable_type)->toBe(Article::class)
        ->and($seo->seoable_id)->toBe($article->id);
});

test('Seo → belongs to seoable model polymorphically', function () {
    $article = Article::factory()->create();
    $seo = Seo::factory()->forModel($article)->create();

    expect($seo->seoable)->not->toBeNull()
        ->and($seo->seoable->id)->toBe($article->id);
});

test('Seo → can be attached to different models', function () {
    $article = Article::factory()->create();
    $page = Page::factory()->create();
    
    $articleSeo = Seo::factory()->forModel($article)->create();
    $pageSeo = Seo::factory()->forModel($page)->create();

    expect($articleSeo->seoable_type)->toBe(Article::class)
        ->and($pageSeo->seoable_type)->toBe(Page::class);
});

test('Seo → has meta fields', function () {
    $article = Article::factory()->create();
    
    $seo = Seo::factory()->forModel($article)->create([
        'title' => 'Meta Title',
        'description' => 'Meta Description',
        'keywords' => 'keyword1, keyword2',
    ]);

    expect($seo->title)->toBe('Meta Title')
        ->and($seo->description)->toBe('Meta Description')
        ->and($seo->keywords)->toBe('keyword1, keyword2');
});

test('Seo → has open graph fields', function () {
    $article = Article::factory()->create();
    
    $seo = Seo::factory()->forModel($article)->create([
        'og_title' => 'OG Title',
        'og_description' => 'OG Description',
        'og_image' => 'https://example.com/image.jpg',
        'og_type' => 'article',
    ]);

    expect($seo->og_title)->toBe('OG Title')
        ->and($seo->og_description)->toBe('OG Description')
        ->and($seo->og_image)->toBe('https://example.com/image.jpg')
        ->and($seo->og_type)->toBe('article');
});

test('Seo → has twitter card fields', function () {
    $article = Article::factory()->create();
    
    $seo = Seo::factory()->forModel($article)->create([
        'twitter_card' => 'summary_large_image',
        'twitter_title' => 'Twitter Title',
        'twitter_description' => 'Twitter Description',
    ]);

    expect($seo->twitter_card)->toBe('summary_large_image')
        ->and($seo->twitter_title)->toBe('Twitter Title')
        ->and($seo->twitter_description)->toBe('Twitter Description');
});

test('Seo → has canonical url', function () {
    $article = Article::factory()->create();
    
    $seo = Seo::factory()->forModel($article)->create([
        'canonical_url' => 'https://example.com/canonical',
    ]);

    expect($seo->canonical_url)->toBe('https://example.com/canonical');
});

test('Seo → has no_index and no_follow flags', function () {
    $article = Article::factory()->create();
    
    $seo = Seo::factory()->forModel($article)->create([
        'no_index' => true,
        'no_follow' => true,
    ]);

    expect($seo->no_index)->toBeTrue()
        ->and($seo->no_follow)->toBeTrue();
});

test('Seo → casts boolean fields', function () {
    $article = Article::factory()->create();
    
    $seo = Seo::factory()->forModel($article)->create([
        'no_index' => 1,
        'no_follow' => 0,
    ]);

    expect($seo->no_index)->toBeBool()
        ->and($seo->no_index)->toBeTrue()
        ->and($seo->no_follow)->toBeBool()
        ->and($seo->no_follow)->toBeFalse();
});

test('Seo → factory creates valid seo', function () {
    $seo = Seo::factory()->create();

    expect($seo->title)->toBeString()
        ->and($seo->description)->toBeString()
        ->and($seo->seoable_type)->not->toBeNull()
        ->and($seo->seoable_id)->toBeInt();
});
