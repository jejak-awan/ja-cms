<?php

use App\Modules\User\Models\User;
use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;

describe('ArticleController', function () {
    
    beforeEach(function () {
        $this->admin = createAdmin();
        $this->actingAs($this->admin);
    });
    
    test('index page loads successfully', function () {
        $response = $this->get(route('admin.articles.index'));
        
        $response->assertOk();
        $response->assertViewIs('admin.articles.index');
    });
    
    test('index displays articles', function () {
        Article::factory()->count(5)->create();
        
        $response = $this->get(route('admin.articles.index'));
        
        $response->assertOk();
        $response->assertViewHas('articles');
    });
    
    test('create page loads successfully', function () {
        $response = $this->get(route('admin.articles.create'));
        
        $response->assertOk();
        $response->assertViewIs('admin.articles.create');
    });
    
    test('store creates new article', function () {
        $category = Category::factory()->create();
        
        $data = [
            'title' => 'Test Article',
            'content' => 'Test content for the article',
            'status' => 'draft',
            'category_id' => $category->id,
        ];
        
        $response = $this->post(route('admin.articles.store'), $data);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
        ]);
    });
    
    test('store validates required fields', function () {
        $response = $this->post(route('admin.articles.store'), []);
        
        $response->assertSessionHasErrors(['title', 'content']);
    });
    
    test('edit page loads successfully', function () {
        $article = Article::factory()->create();
        
        $response = $this->get(route('admin.articles.edit', $article));
        
        $response->assertOk();
        $response->assertViewIs('admin.articles.edit');
        $response->assertViewHas('article');
    });
    
    test('update modifies existing article', function () {
        $article = Article::factory()->create(['title' => 'Old Title']);
        
        $data = [
            'title' => 'New Title',
            'content' => $article->content,
            'status' => $article->status,
            'category_id' => $article->category_id,
        ];
        
        $response = $this->put(route('admin.articles.update', $article), $data);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'New Title',
        ]);
    });
    
    test('destroy deletes article', function () {
        $article = Article::factory()->create();
        
        $response = $this->delete(route('admin.articles.destroy', $article));
        
        $response->assertRedirect();
        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    });
    
    test('bulk action can delete multiple articles', function () {
        $articles = Article::factory()->count(3)->create();
        
        $response = $this->post(route('admin.articles.bulk-action'), [
            'action' => 'delete',
            'articles' => $articles->pluck('id')->toArray(),
        ]);
        
        $response->assertRedirect();
        
        foreach ($articles as $article) {
            $this->assertDatabaseMissing('articles', ['id' => $article->id]);
        }
    });
    
    test('unauthorized user cannot access articles', function () {
        auth()->logout();
        
        $response = $this->get(route('admin.articles.index'));
        
        $response->assertRedirect(route('admin.login'));
    });
});
