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

test('UserRequest → validates name max length', function () {
    $data = [
        'name' => str_repeat('a', 256), // Exceeds 255 character limit
        'email' => 'john@example.com',
        'password' => 'password123',
    ];

    $request = new UserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue();
});

test('UserRequest → validates email format', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'invalid-email', // Invalid email format
        'password' => 'password123',
    ];

    $request = new UserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue();
});

test('UserRequest → validates unique email', function () {
    User::factory()->create(['email' => 'existing@example.com']);
    
    $data = [
        'name' => 'John Doe',
        'email' => 'existing@example.com', // Duplicate email
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
        'password' => '123', // Too short
    ];

    $request = new UserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('password'))->toBeTrue();
});

test('UserRequest → validates valid data with strong password', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'StrongPassword123!',
    ];

    $request = new UserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('UserRequest → validates name is string', function () {
    $data = [
        'name' => 123, // Not a string
        'email' => 'john@example.com',
        'password' => 'password123',
    ];

    $request = new UserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue();
});

test('UserRequest → validates email is string', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 123, // Not a string
        'password' => 'password123',
    ];

    $request = new UserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue();
});

test('UserRequest → validates password is string', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 123, // Not a string
    ];

    $request = new UserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('password'))->toBeTrue();
});