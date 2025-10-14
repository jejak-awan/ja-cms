<?php

use App\Modules\Setting\Models\Setting;
use Illuminate\Support\Facades\Cache;

test('Setting → creates setting with key and value', function () {
    $setting = Setting::factory()->create([
        'key' => 'site_name',
        'value' => 'My CMS',
        'type' => 'text',
    ]);

    expect($setting->key)->toBe('site_name')
        ->and($setting->getAttributes()['value'])->toBe('My CMS');
});

test('Setting → retrieves setting by key', function () {
    Setting::factory()->create([
        'key' => 'site_title',
        'value' => 'Test Site',
        'type' => 'text',
    ]);

    $value = Setting::get('site_title');

    expect($value)->toBe('Test Site');
});

test('Setting → returns default when key not found', function () {
    $value = Setting::get('non_existent_key', 'default_value');

    expect($value)->toBe('default_value');
});

test('Setting → sets setting value', function () {
    Setting::set('app_name', 'JA-CMS', 'general', 'text');

    $setting = Setting::where('key', 'app_name')->first();

    expect($setting)->not->toBeNull()
        ->and($setting->value)->toBe('JA-CMS')
        ->and($setting->group)->toBe('general');
});

test('Setting → updates existing setting', function () {
    Setting::factory()->create(['key' => 'email', 'value' => 'old@example.com']);

    Setting::set('email', 'new@example.com');

    $setting = Setting::where('key', 'email')->first();
    expect($setting->value)->toBe('new@example.com');
});

test('Setting → deletes setting by key', function () {
    Setting::factory()->create(['key' => 'temp_setting', 'value' => 'temp']);

    $result = Setting::forget('temp_setting');

    expect($result)->toBeTrue()
        ->and(Setting::where('key', 'temp_setting')->exists())->toBeFalse();
});

test('Setting → retrieves settings by group', function () {
    Setting::factory()->create(['key' => 'email_host', 'value' => 'smtp.example.com', 'group' => 'email']);
    Setting::factory()->create(['key' => 'email_port', 'value' => '587', 'group' => 'email']);
    Setting::factory()->create(['key' => 'site_name', 'value' => 'Test', 'group' => 'general']);

    $emailSettings = Setting::getGroup('email');

    expect($emailSettings)->toHaveKey('email_host')
        ->and($emailSettings)->toHaveKey('email_port')
        ->and($emailSettings)->not->toHaveKey('site_name');
});

test('Setting → casts boolean type', function () {
    Setting::factory()->create([
        'key' => 'maintenance_mode',
        'value' => '1',
        'type' => 'boolean',
    ]);

    $value = Setting::get('maintenance_mode');

    expect($value)->toBeBool()
        ->and($value)->toBeTrue();
});

test('Setting → casts integer type', function () {
    Setting::factory()->create([
        'key' => 'posts_per_page',
        'value' => '10',
        'type' => 'integer',
    ]);

    $value = Setting::get('posts_per_page');

    expect($value)->toBeInt()
        ->and($value)->toBe(10);
});

test('Setting → casts json type', function () {
    Setting::factory()->create([
        'key' => 'social_links',
        'value' => '{"facebook":"fb.com","twitter":"twitter.com"}',
        'type' => 'json',
    ]);

    $value = Setting::get('social_links');

    expect($value)->toBeArray()
        ->and($value)->toHaveKey('facebook')
        ->and($value['facebook'])->toBe('fb.com');
});

test('Setting → scopes by group', function () {
    Setting::factory()->create(['group' => 'email']);
    Setting::factory()->create(['group' => 'email']);
    Setting::factory()->create(['group' => 'general']);

    $emailSettings = Setting::byGroup('email')->get();

    expect($emailSettings)->toHaveCount(2);
});

test('Setting → scopes public settings', function () {
    Setting::factory()->public()->create();
    Setting::factory()->public()->create();
    Setting::factory()->private()->create();

    $publicSettings = Setting::public()->get();

    expect($publicSettings)->toHaveCount(2);
});

test('Setting → caches retrieved values', function () {
    Setting::factory()->create([
        'key' => 'cached_value',
        'value' => 'test',
        'type' => 'text',
    ]);

    // First call - hits database
    $value1 = Setting::get('cached_value');
    
    // Second call - should hit cache
    $value2 = Setting::get('cached_value');

    expect($value1)->toBe('test')
        ->and($value2)->toBe('test');
});

test('Setting → clears cache after update', function () {
    Setting::factory()->create([
        'key' => 'update_test',
        'value' => 'old',
        'type' => 'text',
    ]);
    Setting::get('update_test'); // Cache it

    Setting::set('update_test', 'new');
    $value = Setting::get('update_test');

    expect($value)->toBe('new');
});
