<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

/**
 * Create admin user with all permissions
 */
function createAdmin(): \App\Modules\User\Models\User
{
    $user = \App\Modules\User\Models\User::factory()->create([
        'status' => 'active',
        'email_verified_at' => now(),
    ]);

    // Create admin role with all permissions
    $role = \App\Modules\User\Models\Role::factory()->create([
        'name' => 'Administrator',
        'slug' => 'admin',
        'display_name' => 'Administrator',
        'permissions' => [
            'articles.*',
            'categories.*',
            'pages.*',
            'media.*',
            'users.*',
            'settings.*',
            'themes.*',
            'plugins.*',
        ],
    ]);

    $user->roles()->attach($role->id);

    return $user;
}

/**
 * Create editor user with content permissions
 */
function createEditor(): \App\Modules\User\Models\User
{
    $user = \App\Modules\User\Models\User::factory()->create([
        'status' => 'active',
        'email_verified_at' => now(),
    ]);

    $role = \App\Modules\User\Models\Role::factory()->create([
        'name' => 'Editor',
        'slug' => 'editor',
        'display_name' => 'Editor',
        'permissions' => [
            'articles.*',
            'categories.read',
            'pages.*',
            'media.*',
        ],
    ]);

    $user->roles()->attach($role->id);

    return $user;
}
