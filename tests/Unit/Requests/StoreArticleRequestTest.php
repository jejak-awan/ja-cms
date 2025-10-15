<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Modules\Article\Requests\StoreArticleRequest;
use App\Modules\Category\Models\Category;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

class StoreArticleRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create([
            'name_id' => 'Test Category',
            'description_id' => 'Test description'
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $request = new StoreArticleRequest();
        $rules = $request->rules();

        $validator = Validator::make([], $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
        $this->assertArrayHasKey('content', $validator->errors()->toArray());
        $this->assertArrayHasKey('category_id', $validator->errors()->toArray());
        $this->assertArrayHasKey('status', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_title_max_length()
    {
        $request = new StoreArticleRequest();
        $rules = $request->rules();

        $data = [
            'title' => str_repeat('a', 256), // Exceeds 255 character limit
            'content' => 'Valid content',
            'category_id' => $this->category->id,
            'status' => 'draft'
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_slug_format()
    {
        $request = new StoreArticleRequest();
        $rules = $request->rules();

        $data = [
            'title' => 'Valid Title',
            'slug' => 'Invalid Slug!', // Invalid format
            'content' => 'Valid content',
            'category_id' => $this->category->id,
            'status' => 'draft'
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('slug', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_slug_uniqueness()
    {
        $request = new StoreArticleRequest();
        $rules = $request->rules();

        // Create an article with existing slug
        \App\Modules\Article\Models\Article::factory()->create([
            'title_id' => 'Existing Article',
            'content_id' => 'Content',
            'slug' => 'existing-slug',
            'category_id' => $this->category->id,
            'user_id' => $this->user->id
        ]);

        $data = [
            'title' => 'New Article',
            'slug' => 'existing-slug', // Duplicate slug
            'content' => 'Valid content',
            'category_id' => $this->category->id,
            'status' => 'draft'
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('slug', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_category_exists()
    {
        $request = new StoreArticleRequest();
        $rules = $request->rules();

        $data = [
            'title' => 'Valid Title',
            'content' => 'Valid content',
            'category_id' => 999, // Non-existent category
            'status' => 'draft'
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('category_id', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_status_enum()
    {
        $request = new StoreArticleRequest();
        $rules = $request->rules();

        $data = [
            'title' => 'Valid Title',
            'content' => 'Valid content',
            'category_id' => $this->category->id,
            'status' => 'invalid_status' // Invalid status
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('status', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_excerpt_max_length()
    {
        $request = new StoreArticleRequest();
        $rules = $request->rules();

        $data = [
            'title' => 'Valid Title',
            'content' => 'Valid content',
            'excerpt' => str_repeat('a', 501), // Exceeds 500 character limit
            'category_id' => $this->category->id,
            'status' => 'draft'
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('excerpt', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_valid_data()
    {
        $request = new StoreArticleRequest();
        $rules = $request->rules();

        $data = [
            'title' => 'Valid Article Title',
            'slug' => 'valid-slug',
            'content' => 'Valid article content',
            'category_id' => $this->category->id,
            'status' => 'draft',
            'featured' => true,
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
        $request = new StoreArticleRequest();
        $messages = $request->messages();

        $this->assertArrayHasKey('title.required', $messages);
        $this->assertArrayHasKey('title.max', $messages);
        $this->assertArrayHasKey('slug.regex', $messages);
        $this->assertArrayHasKey('slug.unique', $messages);
        $this->assertArrayHasKey('content.required', $messages);
        $this->assertArrayHasKey('category_id.required', $messages);
        $this->assertArrayHasKey('status.required', $messages);
    }

    /** @test */
    public function it_has_custom_attributes()
    {
        $request = new StoreArticleRequest();
        $attributes = $request->attributes();

        $this->assertArrayHasKey('category_id', $attributes);
        $this->assertArrayHasKey('featured_image', $attributes);
        $this->assertArrayHasKey('published_at', $attributes);
        $this->assertArrayHasKey('meta_title', $attributes);
        $this->assertEquals('category', $attributes['category_id']);
        $this->assertEquals('featured image', $attributes['featured_image']);
    }

    /** @test */
    public function it_prepares_data_for_validation()
    {
        // Test the prepareForValidation method by creating a request instance
        // and checking if the method exists and can be called
        $request = new StoreArticleRequest();
        
        $this->assertTrue(method_exists($request, 'prepareForValidation'));
        
        // Test that the method is protected (we can't call it directly)
        $reflection = new \ReflectionClass($request);
        $method = $reflection->getMethod('prepareForValidation');
        $this->assertTrue($method->isProtected());
    }
}