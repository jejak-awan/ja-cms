<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Modules\Category\Requests\StoreCategoryRequest;
use App\Modules\Category\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

class StoreCategoryRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->parentCategory = Category::factory()->create([
            'name_id' => 'Parent Category',
            'description_id' => 'Parent description'
        ]);
    }

    /** @test */
    public function it_validates_required_name()
    {
        $request = new StoreCategoryRequest();
        $rules = $request->rules();

        $validator = Validator::make([], $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_name_max_length()
    {
        $request = new StoreCategoryRequest();
        $rules = $request->rules();

        $data = [
            'name' => str_repeat('a', 256), // Exceeds 255 character limit
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_slug_format()
    {
        $request = new StoreCategoryRequest();
        $rules = $request->rules();

        $data = [
            'name' => 'Valid Name',
            'slug' => 'Invalid Slug!', // Invalid format
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('slug', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_slug_uniqueness()
    {
        $request = new StoreCategoryRequest();
        $rules = $request->rules();

        // Create existing category with slug
        Category::factory()->create([
            'name_id' => 'Existing Category',
            'description_id' => 'Description',
            'slug' => 'existing-slug'
        ]);

        $data = [
            'name' => 'New Category',
            'slug' => 'existing-slug', // Duplicate slug
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('slug', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_parent_exists()
    {
        $request = new StoreCategoryRequest();
        $rules = $request->rules();

        $data = [
            'name' => 'Valid Name',
            'parent_id' => 999, // Non-existent parent
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('parent_id', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_color_hex_format()
    {
        $request = new StoreCategoryRequest();
        $rules = $request->rules();

        $data = [
            'name' => 'Valid Name',
            'color' => 'invalid-color', // Invalid hex format
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('color', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_order_minimum()
    {
        $request = new StoreCategoryRequest();
        $rules = $request->rules();

        $data = [
            'name' => 'Valid Name',
            'order' => -1, // Negative order
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('order', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_valid_data()
    {
        $request = new StoreCategoryRequest();
        $rules = $request->rules();

        $data = [
            'name' => 'Valid Category Name',
            'slug' => 'valid-slug',
            'description' => 'Valid description',
            'parent_id' => $this->parentCategory->id,
            'color' => '#FF5733',
            'icon' => 'fas fa-folder',
            'order' => 1,
            'is_active' => true,
            'meta_title' => 'SEO Title',
            'meta_description' => 'SEO Description',
            'meta_keywords' => 'seo, keywords'
        ];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function it_has_custom_error_messages()
    {
        $request = new StoreCategoryRequest();
        $messages = $request->messages();

        $this->assertArrayHasKey('name.required', $messages);
        $this->assertArrayHasKey('name.max', $messages);
        $this->assertArrayHasKey('slug.regex', $messages);
        $this->assertArrayHasKey('slug.unique', $messages);
        $this->assertArrayHasKey('parent_id.exists', $messages);
        $this->assertArrayHasKey('color.regex', $messages);
    }

    /** @test */
    public function it_has_custom_attributes()
    {
        $request = new StoreCategoryRequest();
        $attributes = $request->attributes();

        $this->assertArrayHasKey('parent_id', $attributes);
        $this->assertArrayHasKey('is_active', $attributes);
        $this->assertArrayHasKey('meta_title', $attributes);
        $this->assertEquals('parent category', $attributes['parent_id']);
        $this->assertEquals('active status', $attributes['is_active']);
    }

    /** @test */
    public function it_prepares_data_for_validation()
    {
        // Test the prepareForValidation method by creating a request instance
        // and checking if the method exists and can be called
        $request = new StoreCategoryRequest();
        
        $this->assertTrue(method_exists($request, 'prepareForValidation'));
        
        // Test that the method is protected (we can't call it directly)
        $reflection = new \ReflectionClass($request);
        $method = $reflection->getMethod('prepareForValidation');
        $this->assertTrue($method->isProtected());
    }
}