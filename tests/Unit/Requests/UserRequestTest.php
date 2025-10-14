<?php

use App\Modules\User\Requests\UserRequest;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Validator;

test('UserRequest → validates required fields', function () {
    $request = new UserRequest();
    $validator = Validator::make([], $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue()
        ->and($validator->errors()->has('password'))->toBeTrue();
});

test('UserRequest → passes with valid data', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
    ];

    $request = new UserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('UserRequest → validates email format', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'invalid-email',
        'password' => 'password123',
    ];

    $request = new UserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue();
});

test('UserRequest → validates unique email', function () {
    $existing = User::factory()->create(['email' => 'existing@example.com']);
    
    $data = [
        'name' => 'John Doe',
        'email' => 'existing@example.com',
        'password' => 'password123',
    ];

    $request = new UserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue();
});

test('UserRequest → validates password minimum length', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'short',
    ];

    $request = new UserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('password'))->toBeTrue();
});

test('UserRequest → validates name max length', function () {
    $data = [
        'name' => str_repeat('a', 256),
        'email' => 'john@example.com',
        'password' => 'password123',
    ];

    $request = new UserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue();
});
