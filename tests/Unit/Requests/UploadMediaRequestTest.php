<?php

use App\Modules\Media\Requests\UploadMediaRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('UploadMediaRequest → validates required file', function () {
    $request = new UploadMediaRequest();
    $validator = Validator::make([], $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('file'))->toBeTrue();
});

test('UploadMediaRequest → passes with valid file', function () {
    Storage::fake('public');
    
    $file = UploadedFile::fake()->image('test.jpg');
    
    $data = [
        'file' => $file,
        'folder' => 'uploads',
        'alt_text' => 'Test image',
        'description' => 'Test description',
    ];

    $request = new UploadMediaRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('UploadMediaRequest → validates file size', function () {
    Storage::fake('public');
    
    // Create a file larger than 10MB
    $file = UploadedFile::fake()->create('large-file.pdf', 10241); // 10.241 MB
    
    $data = [
        'file' => $file,
    ];

    $request = new UploadMediaRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('file'))->toBeTrue();
});

test('UploadMediaRequest → validates file mime types', function () {
    Storage::fake('public');
    
    // Create a file with unsupported mime type
    $file = UploadedFile::fake()->create('test.exe', 1000, 'application/x-msdownload');
    
    $data = [
        'file' => $file,
    ];

    $request = new UploadMediaRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('file'))->toBeTrue();
});

test('UploadMediaRequest → accepts valid image files', function () {
    Storage::fake('public');
    
    $validImages = [
        'test.jpg' => 'image/jpeg',
        'test.png' => 'image/png',
        'test.gif' => 'image/gif',
        'test.webp' => 'image/webp',
    ];

    foreach ($validImages as $filename => $mimeType) {
        $file = UploadedFile::fake()->create($filename, 1000, $mimeType);
        
        $data = ['file' => $file];
        $request = new UploadMediaRequest();
        $validator = Validator::make($data, $request->rules());

        expect($validator->passes())->toBeTrue("Failed for {$filename}");
    }
});

test('UploadMediaRequest → accepts valid document files', function () {
    Storage::fake('public');
    
    $validDocuments = [
        'test.pdf' => 'application/pdf',
        'test.doc' => 'application/msword',
        'test.docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'test.xls' => 'application/vnd.ms-excel',
        'test.xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'test.ppt' => 'application/vnd.ms-powerpoint',
        'test.pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'test.txt' => 'text/plain',
    ];

    foreach ($validDocuments as $filename => $mimeType) {
        $file = UploadedFile::fake()->create($filename, 1000, $mimeType);
        
        $data = ['file' => $file];
        $request = new UploadMediaRequest();
        $validator = Validator::make($data, $request->rules());

        expect($validator->passes())->toBeTrue("Failed for {$filename}");
    }
});

test('UploadMediaRequest → accepts valid archive files', function () {
    Storage::fake('public');
    
    $validArchives = [
        'test.zip' => 'application/zip',
        'test.rar' => 'application/x-rar-compressed',
    ];

    foreach ($validArchives as $filename => $mimeType) {
        $file = UploadedFile::fake()->create($filename, 1000, $mimeType);
        
        $data = ['file' => $file];
        $request = new UploadMediaRequest();
        $validator = Validator::make($data, $request->rules());

        expect($validator->passes())->toBeTrue("Failed for {$filename}");
    }
});

test('UploadMediaRequest → validates folder max length', function () {
    Storage::fake('public');
    
    $file = UploadedFile::fake()->image('test.jpg');
    
    $data = [
        'file' => $file,
        'folder' => str_repeat('a', 256), // Exceeds 255 character limit
    ];

    $request = new UploadMediaRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('folder'))->toBeTrue();
});

test('UploadMediaRequest → validates alt_text max length', function () {
    Storage::fake('public');
    
    $file = UploadedFile::fake()->image('test.jpg');
    
    $data = [
        'file' => $file,
        'alt_text' => str_repeat('a', 256), // Exceeds 255 character limit
    ];

    $request = new UploadMediaRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('alt_text'))->toBeTrue();
});

test('UploadMediaRequest → validates description max length', function () {
    Storage::fake('public');
    
    $file = UploadedFile::fake()->image('test.jpg');
    
    $data = [
        'file' => $file,
        'description' => str_repeat('a', 1001), // Exceeds 1000 character limit
    ];

    $request = new UploadMediaRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('description'))->toBeTrue();
});

test('UploadMediaRequest → validates valid data with all fields', function () {
    Storage::fake('public');
    
    $file = UploadedFile::fake()->image('test.jpg');
    
    $data = [
        'file' => $file,
        'folder' => 'uploads/images',
        'alt_text' => 'Test image alt text',
        'description' => 'Test image description',
    ];

    $request = new UploadMediaRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('UploadMediaRequest → has custom error messages', function () {
    $request = new UploadMediaRequest();
    $messages = $request->messages();

    expect($messages)->toHaveKey('file.required')
        ->and($messages)->toHaveKey('file.file')
        ->and($messages)->toHaveKey('file.max')
        ->and($messages)->toHaveKey('file.mimes');
});

test('UploadMediaRequest → has custom attributes', function () {
    $request = new UploadMediaRequest();
    $attributes = $request->attributes();

    expect($attributes)->toHaveKey('file')
        ->and($attributes)->toHaveKey('folder')
        ->and($attributes)->toHaveKey('alt_text')
        ->and($attributes)->toHaveKey('description')
        ->and($attributes['file'])->toBe('File');
});
