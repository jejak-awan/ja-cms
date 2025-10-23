<?php

use App\Modules\Page\Requests\StorePageRequest;
use App\Modules\Page\Models\Page;
use Illuminate\Support\Facades\Validator;

test('StorePageRequest → validates required fields', function () {
    $request = new StorePageRequest();
    $validator = Validator::make([], $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('title_id'))->toBeTrue()
        ->and($validator->errors()->has('content_id'))->toBeTrue()
        ->and($validator->errors()->has('status'))->toBeTrue();
});

test('StorePageRequest → passes with valid data', function () {
    $data = [
        'title_id' => 'Test Page',
        'content_id' => 'Test page content',
        'status' => 'draft',
    ];

    $request = new StorePageRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('StorePageRequest → validates slug format', function () {
    $data = [
        'title_id' => 'Test Page',
        'slug' => 'Invalid Slug!',
        'content_id' => 'Test content',
        'status' => 'draft',
    ];

    $request = new StorePageRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('slug'))->toBeTrue();
});

test('StorePageRequest → validates unique slug', function () {
    $existing = Page::factory()->create(['slug' => 'existing-page']);
    
    $data = [
        'title_id' => 'Test Page',
        'slug' => 'existing-page',
        'content_id' => 'Test content',
        'status' => 'draft',
    ];

    $request = new StorePageRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('slug'))->toBeTrue();
});

test('StorePageRequest → validates status enum', function () {
    $data = [
        'title_id' => 'Test Page',
        'content_id' => 'Test content',
        'status' => 'invalid-status',
    ];

    $request = new StorePageRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('status'))->toBeTrue();
});

test('StorePageRequest → validates parent exists', function () {
    $data = [
        'title_id' => 'Test Page',
        'content_id' => 'Test content',
        'status' => 'draft',
        'parent_id' => 99999,
    ];

    $request = new StorePageRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('parent_id'))->toBeTrue();
});
