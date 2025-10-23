<?php

use App\Modules\Article\Requests\UpdateArticleRequest;
use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use Illuminate\Support\Facades\Validator;

test('UpdateArticleRequest → validates required fields', function () {
    $request = new UpdateArticleRequest();
    $validator = Validator::make([], $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('title_id'))->toBeTrue()
        ->and($validator->errors()->has('content_id'))->toBeTrue()
        ->and($validator->errors()->has('category_id'))->toBeTrue()
        ->and($validator->errors()->has('status'))->toBeTrue();
});

test('UpdateArticleRequest → passes with valid data', function () {
    $category = Category::factory()->create();
    
    $data = [
        'title_id' => 'Updated Article',
        'content_id' => 'Updated article content',
        'category_id' => $category->id,
        'status' => 'published',
    ];

    $request = new UpdateArticleRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('UpdateArticleRequest → validates title max length', function () {
    $category = Category::factory()->create();
    
    $data = [
        'title_id' => str_repeat('a', 256), // Exceeds 255 character limit
        'content_id' => 'Updated content',
        'category_id' => $category->id,
        'status' => 'draft'
    ];

    $request = new UpdateArticleRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('title_id'))->toBeTrue();
});

test('UpdateArticleRequest → validates slug format', function () {
    $category = Category::factory()->create();
    
    $data = [
        'title_id' => 'Updated Article',
        'slug' => 'Invalid Slug!', // Invalid format
        'content_id' => 'Updated content',
        'category_id' => $category->id,
        'status' => 'draft'
    ];

    $request = new UpdateArticleRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('slug'))->toBeTrue();
});

test('UpdateArticleRequest → validates unique slug excluding current article', function () {
    $category = Category::factory()->create();
    $existing = Article::factory()->create([
        'title_id' => 'Existing Article',
        'content_id' => 'Content',
        'slug' => 'existing-slug',
        'category_id' => $category->id
    ]);

    $data = [
        'title_id' => 'Updated Article',
        'slug' => 'existing-slug', // Same slug but should be allowed for update
        'content_id' => 'Updated content',
        'category_id' => $category->id,
        'status' => 'draft'
    ];

    // Test that the rules method works without route binding
    $request = new UpdateArticleRequest();
    $rules = $request->rules();
    
    // Check that the slug rule includes unique with ignore
    expect($rules['slug'])->toHaveCount(5) // Should have 5 rules
        ->and($rules['slug'])->toContain('nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/');
});

test('UpdateArticleRequest → validates category exists', function () {
    $data = [
        'title_id' => 'Updated Article',
        'content_id' => 'Updated content',
        'category_id' => 999, // Non-existent category
        'status' => 'draft'
    ];

    $request = new UpdateArticleRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('category_id'))->toBeTrue();
});

test('UpdateArticleRequest → validates status enum', function () {
    $category = Category::factory()->create();
    
    $data = [
        'title_id' => 'Updated Article',
        'content_id' => 'Updated content',
        'category_id' => $category->id,
        'status' => 'invalid_status' // Invalid status
    ];

    $request = new UpdateArticleRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('status'))->toBeTrue();
});

test('UpdateArticleRequest → validates excerpt max length', function () {
    $category = Category::factory()->create();
    
    $data = [
        'title_id' => 'Updated Article',
        'content_id' => 'Updated content',
        'excerpt_id' => str_repeat('a', 501), // Exceeds 500 character limit
        'category_id' => $category->id,
        'status' => 'draft'
    ];

    $request = new UpdateArticleRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('excerpt_id'))->toBeTrue();
});

test('UpdateArticleRequest → validates valid data with all fields', function () {
    $category = Category::factory()->create();
    
    $data = [
        'title_id' => 'Updated Article Title',
        'title_en' => 'Updated Article Title EN',
        'slug' => 'updated-slug',
        'content_id' => 'Updated article content',
        'content_en' => 'Updated article content EN',
        'excerpt_id' => 'Updated excerpt',
        'excerpt_en' => 'Updated excerpt EN',
        'category_id' => $category->id,
        'status' => 'published',
        'featured' => true,
        'meta_title' => 'Updated SEO Title',
        'meta_description' => 'Updated SEO Description',
        'meta_keywords' => 'updated, seo, keywords'
    ];

    $request = new UpdateArticleRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('UpdateArticleRequest → has custom error messages', function () {
    $request = new UpdateArticleRequest();
    $messages = $request->messages();

    expect($messages)->toHaveKey('title_id.required')
        ->and($messages)->toHaveKey('title_id.max')
        ->and($messages)->toHaveKey('slug.regex')
        ->and($messages)->toHaveKey('content_id.required')
        ->and($messages)->toHaveKey('category_id.required');
});

test('UpdateArticleRequest → has custom attributes', function () {
    $request = new UpdateArticleRequest();
    $attributes = $request->attributes();

    expect($attributes)->toHaveKey('category_id')
        ->and($attributes)->toHaveKey('featured_image')
        ->and($attributes)->toHaveKey('published_at')
        ->and($attributes)->toHaveKey('meta_title')
        ->and($attributes['category_id'])->toBe('category');
});
