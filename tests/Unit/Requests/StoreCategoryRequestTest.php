<?php

use App\Modules\Category\Requests\StoreCategoryRequest;
use App\Modules\Category\Models\Category;
use Illuminate\Support\Facades\Validator;

test('StoreCategoryRequest → validates required fields', function () {
    $request = new StoreCategoryRequest();
    $validator = Validator::make([], $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue();
});

test('StoreCategoryRequest → passes with valid data', function () {
    $data = [
        'name' => 'Test Category',
        'description' => 'Test description',
    ];

    $request = new StoreCategoryRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('StoreCategoryRequest → validates slug format', function () {
    $data = [
        'name' => 'Test Category',
        'slug' => 'Invalid Slug!',
    ];

    $request = new StoreCategoryRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('slug'))->toBeTrue();
});

test('StoreCategoryRequest → validates unique slug', function () {
    $existing = Category::factory()->create(['slug' => 'existing-slug']);
    
    $data = [
        'name' => 'Test Category',
        'slug' => 'existing-slug',
    ];

    $request = new StoreCategoryRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('slug'))->toBeTrue();
});

test('StoreCategoryRequest → validates parent exists', function () {
    $data = [
        'name' => 'Test Category',
        'parent_id' => 99999,
    ];

    $request = new StoreCategoryRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('parent_id'))->toBeTrue();
});
