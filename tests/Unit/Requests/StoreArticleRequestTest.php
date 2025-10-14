<?php

use App\Modules\Article\Requests\StoreArticleRequest;
use App\Modules\Category\Models\Category;
use App\Modules\Tag\Models\Tag;
use Illuminate\Support\Facades\Validator;

test('StoreArticleRequest → validates required fields', function () {
    $request = new StoreArticleRequest();
    $validator = Validator::make([], $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('title'))->toBeTrue()
        ->and($validator->errors()->has('content'))->toBeTrue()
        ->and($validator->errors()->has('category_id'))->toBeTrue()
        ->and($validator->errors()->has('status'))->toBeTrue();
});

test('StoreArticleRequest → passes with valid data', function () {
    $category = Category::factory()->create();
    
    $data = [
        'title' => 'Test Article',
        'content' => 'Test content for article',
        'category_id' => $category->id,
        'status' => 'draft',
    ];

    $request = new StoreArticleRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('StoreArticleRequest → validates slug format', function () {
    $category = Category::factory()->create();
    
    $data = [
        'title' => 'Test Article',
        'slug' => 'Invalid Slug!',
        'content' => 'Test content',
        'category_id' => $category->id,
        'status' => 'draft',
    ];

    $request = new StoreArticleRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('slug'))->toBeTrue();
});

test('StoreArticleRequest → validates unique slug', function () {
    $category = Category::factory()->create();
    $existingArticle = \App\Modules\Article\Models\Article::factory()->create(['slug' => 'existing-slug']);
    
    $data = [
        'title' => 'Test Article',
        'slug' => 'existing-slug',
        'content' => 'Test content',
        'category_id' => $category->id,
        'status' => 'draft',
    ];

    $request = new StoreArticleRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('slug'))->toBeTrue();
});

test('StoreArticleRequest → validates status enum', function () {
    $category = Category::factory()->create();
    
    $data = [
        'title' => 'Test Article',
        'content' => 'Test content',
        'category_id' => $category->id,
        'status' => 'invalid-status',
    ];

    $request = new StoreArticleRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('status'))->toBeTrue();
});

test('StoreArticleRequest → validates category exists', function () {
    $data = [
        'title' => 'Test Article',
        'content' => 'Test content',
        'category_id' => 99999,
        'status' => 'draft',
    ];

    $request = new StoreArticleRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('category_id'))->toBeTrue();
});

test('StoreArticleRequest → validates tags array', function () {
    $category = Category::factory()->create();
    $tag = Tag::factory()->create();
    
    $data = [
        'title' => 'Test Article',
        'content' => 'Test content',
        'category_id' => $category->id,
        'status' => 'draft',
        'tags' => [$tag->id],
    ];

    $request = new StoreArticleRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('StoreArticleRequest → validates max lengths', function () {
    $category = Category::factory()->create();
    
    $data = [
        'title' => str_repeat('a', 256), // exceeds 255
        'content' => 'Test content',
        'category_id' => $category->id,
        'status' => 'draft',
    ];

    $request = new StoreArticleRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('title'))->toBeTrue();
});
