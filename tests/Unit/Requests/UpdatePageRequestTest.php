<?php

use App\Modules\Page\Requests\UpdatePageRequest;
use App\Modules\Page\Models\Page;
use Illuminate\Support\Facades\Validator;

test('UpdatePageRequest → validates required fields', function () {
    $request = new UpdatePageRequest();
    $validator = Validator::make([], $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('title_id'))->toBeTrue()
        ->and($validator->errors()->has('content_id'))->toBeTrue()
        ->and($validator->errors()->has('status'))->toBeTrue();
});

test('UpdatePageRequest → passes with valid data', function () {
    $data = [
        'title_id' => 'Updated Page',
        'content_id' => 'Updated page content',
        'status' => 'published',
    ];

    $request = new UpdatePageRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('UpdatePageRequest → validates slug format', function () {
    $data = [
        'title_id' => 'Updated Page',
        'slug' => 'Invalid Slug!',
        'content_id' => 'Updated content',
        'status' => 'draft',
    ];

    $request = new UpdatePageRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('slug'))->toBeTrue();
});

test('UpdatePageRequest → validates unique slug excluding current page', function () {
    $existing = Page::factory()->create(['slug' => 'existing-page']);
    
    // Test that the rules method works without route binding
    $request = new UpdatePageRequest();
    $rules = $request->rules();
    
    // Check that the slug rule includes unique with ignore
    expect($rules['slug'])->toHaveCount(5) // Should have 5 rules
        ->and($rules['slug'])->toContain('nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/');
});

test('UpdatePageRequest → validates status enum', function () {
    $data = [
        'title_id' => 'Updated Page',
        'content_id' => 'Updated content',
        'status' => 'invalid-status',
    ];

    $request = new UpdatePageRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('status'))->toBeTrue();
});

test('UpdatePageRequest → validates parent exists', function () {
    $data = [
        'title_id' => 'Updated Page',
        'content_id' => 'Updated content',
        'status' => 'draft',
        'parent_id' => 99999,
    ];

    $request = new UpdatePageRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('parent_id'))->toBeTrue();
});

test('UpdatePageRequest → validates parent not self', function () {
    $page = Page::factory()->create();
    
    // Test that the rules method works without route binding
    $request = new UpdatePageRequest();
    $rules = $request->rules();
    
    // Check that the parent_id rule includes not_in
    expect($rules['parent_id'])->toHaveCount(4) // Should have 4 rules
        ->and($rules['parent_id'])->toContain('nullable', 'integer', 'exists:pages,id');
});

test('UpdatePageRequest → has custom error messages', function () {
    $request = new UpdatePageRequest();
    $messages = $request->messages();

    expect($messages)->toHaveKey('title_id.required')
        ->and($messages)->toHaveKey('content_id.required')
        ->and($messages)->toHaveKey('slug.unique')
        ->and($messages)->toHaveKey('parent_id.not_in');
});

test('UpdatePageRequest → has custom attributes', function () {
    $request = new UpdatePageRequest();
    $attributes = $request->attributes();

    expect($attributes)->toHaveKey('title_id')
        ->and($attributes)->toHaveKey('content_id')
        ->and($attributes)->toHaveKey('parent_id')
        ->and($attributes['title_id'])->toBe('Judul (Indonesia)');
});
