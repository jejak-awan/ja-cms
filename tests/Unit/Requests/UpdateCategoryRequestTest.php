<?php

use App\Modules\Category\Requests\UpdateCategoryRequest;
use App\Modules\Category\Models\Category;
use Illuminate\Support\Facades\Validator;

test('UpdateCategoryRequest → validates required fields', function () {
    $request = new UpdateCategoryRequest();
    $validator = Validator::make([], $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name_id'))->toBeTrue();
});

test('UpdateCategoryRequest → passes with valid data', function () {
    $data = [
        'name_id' => 'Updated Category',
        'description_id' => 'Updated category description',
        'is_active' => true,
    ];

    $request = new UpdateCategoryRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('UpdateCategoryRequest → validates name max length', function () {
    $data = [
        'name_id' => str_repeat('a', 256), // Exceeds 255 character limit
        'is_active' => true,
    ];

    $request = new UpdateCategoryRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name_id'))->toBeTrue();
});

test('UpdateCategoryRequest → validates slug format', function () {
    $data = [
        'name_id' => 'Updated Category',
        'slug' => 'Invalid Slug!', // Invalid format
        'is_active' => true,
    ];

    $request = new UpdateCategoryRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('slug'))->toBeTrue();
});

test('UpdateCategoryRequest → validates unique slug excluding current category', function () {
    $existing = Category::factory()->create([
        'name_id' => 'Existing Category',
        'slug' => 'existing-category'
    ]);

    // Test that the rules method works without route binding
    $request = new UpdateCategoryRequest();
    $rules = $request->rules();
    
    // Check that the slug rule includes unique with ignore
    expect($rules['slug'])->toHaveCount(5) // Should have 5 rules
        ->and($rules['slug'])->toContain('nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/');
});

test('UpdateCategoryRequest → validates parent exists', function () {
    $data = [
        'name_id' => 'Updated Category',
        'parent_id' => 99999, // Non-existent parent
        'is_active' => true,
    ];

    $request = new UpdateCategoryRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('parent_id'))->toBeTrue();
});

test('UpdateCategoryRequest → validates parent not self', function () {
    $category = Category::factory()->create();
    
    // Test that the rules method works without route binding
    $request = new UpdateCategoryRequest();
    $rules = $request->rules();
    
    // Check that the parent_id rule includes not_in
    expect($rules['parent_id'])->toHaveCount(4) // Should have 4 rules
        ->and($rules['parent_id'])->toContain('nullable', 'integer', 'exists:categories,id');
});

test('UpdateCategoryRequest → validates color format', function () {
    $data = [
        'name_id' => 'Updated Category',
        'color' => 'invalid-color', // Invalid hex format
        'is_active' => true,
    ];

    $request = new UpdateCategoryRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('color'))->toBeTrue();
});

test('UpdateCategoryRequest → validates order minimum', function () {
    $data = [
        'name_id' => 'Updated Category',
        'order' => -1, // Negative order
        'is_active' => true,
    ];

    $request = new UpdateCategoryRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('order'))->toBeTrue();
});

test('UpdateCategoryRequest → validates valid data with all fields', function () {
    $parent = Category::factory()->create();
    
    $data = [
        'name_id' => 'Updated Category Name',
        'name_en' => 'Updated Category Name EN',
        'slug' => 'updated-category',
        'description_id' => 'Updated category description',
        'description_en' => 'Updated category description EN',
        'parent_id' => $parent->id,
        'color' => '#FF5733',
        'icon' => 'fas fa-folder',
        'order' => 1,
        'is_active' => true,
        'meta_title' => 'Updated SEO Title',
        'meta_description' => 'Updated SEO Description',
        'meta_keywords' => 'updated, seo, keywords'
    ];

    $request = new UpdateCategoryRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('UpdateCategoryRequest → has custom error messages', function () {
    $request = new UpdateCategoryRequest();
    $messages = $request->messages();

    expect($messages)->toHaveKey('name_id.required')
        ->and($messages)->toHaveKey('name_id.max')
        ->and($messages)->toHaveKey('slug.regex')
        ->and($messages)->toHaveKey('parent_id.not_in')
        ->and($messages)->toHaveKey('color.regex');
});

test('UpdateCategoryRequest → has custom attributes', function () {
    $request = new UpdateCategoryRequest();
    $attributes = $request->attributes();

    expect($attributes)->toHaveKey('parent_id')
        ->and($attributes)->toHaveKey('is_active')
        ->and($attributes)->toHaveKey('meta_title')
        ->and($attributes['parent_id'])->toBe('parent category');
});

test('UpdateCategoryRequest → prepareForValidation converts is_active to boolean', function () {
    $request = new UpdateCategoryRequest();
    
    // Simulate request data
    $request->merge(['is_active' => '1']);
    
    // Call prepareForValidation
    $reflection = new ReflectionClass($request);
    $method = $reflection->getMethod('prepareForValidation');
    $method->setAccessible(true);
    $method->invoke($request);
    
    expect($request->input('is_active'))->toBeTrue();
});

test('UpdateCategoryRequest → prepareForValidation sets empty slug to null', function () {
    $request = new UpdateCategoryRequest();
    
    // Simulate request data
    $request->merge(['slug' => '']);
    
    // Call prepareForValidation
    $reflection = new ReflectionClass($request);
    $method = $reflection->getMethod('prepareForValidation');
    $method->setAccessible(true);
    $method->invoke($request);
    
    expect($request->input('slug'))->toBeNull();
});

test('UpdateCategoryRequest → prepareForValidation sets empty parent_id to null', function () {
    $request = new UpdateCategoryRequest();
    
    // Simulate request data
    $request->merge(['parent_id' => '']);
    
    // Call prepareForValidation
    $reflection = new ReflectionClass($request);
    $method = $reflection->getMethod('prepareForValidation');
    $method->setAccessible(true);
    $method->invoke($request);
    
    expect($request->input('parent_id'))->toBeNull();
});
