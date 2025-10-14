# 🚀 Quick Start Guide - Development Sprint

**For:** JA-CMS v2.0 Development  
**Date:** October 14, 2025  
**First Sprint:** Testing Infrastructure

---

## 📋 Pre-Development Checklist

### 1. Environment Setup
```bash
# Ensure you have:
- PHP 8.3+
- Composer 2.x
- Node.js 20+
- Redis server
- SQLite/MySQL

# Check versions
php -v
composer -v
node -v
npm -v
redis-server --version
```

### 2. Development Tools
```bash
# Install recommended VS Code extensions
- PHP Intelephense
- Laravel Extra Intellisense
- Tailwind CSS IntelliSense
- ESLint
- Prettier
- GitLens

# Or install via command
code --install-extension bmewburn.vscode-intelephense-client
code --install-extension amiralizadeh9480.laravel-extra-intellisense
code --install-extension bradlc.vscode-tailwindcss
```

### 3. Project Dependencies
```bash
cd /var/www/html/cms-app

# Update dependencies
composer update
npm install

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## 🧪 SPRINT 1: Testing Setup (Start Immediately)

### Step 1: Install Pest PHP (Better than PHPUnit)

```bash
# Install Pest
composer require pestphp/pest --dev --with-all-dependencies
composer require pestphp/pest-plugin-laravel --dev

# Initialize Pest
./vendor/bin/pest --init

# Run sample test
./vendor/bin/pest
```

### Step 2: Configure Testing Environment

**Create/Update `phpunit.xml`:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
         processIsolation="false"
         stopOnFailure="false"
         cacheDirectory=".phpunit.cache">
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
    </php>
    <source>
        <include>
            <directory suffix=".php">./app</directory>
        </include>
    </source>
    <coverage>
        <report>
            <html outputDirectory="coverage-report"/>
            <text outputFile="coverage.txt"/>
        </report>
    </coverage>
</phpunit>
```

### Step 3: Create First Test (Article Model)

**File: `tests/Unit/Models/ArticleTest.php`**
```php
<?php

use App\Modules\Article\Models\Article;
use App\Modules\User\Models\User;
use App\Modules\Category\Models\Category;

test('article has correct fillable attributes', function () {
    $article = new Article();
    
    expect($article->getFillable())->toContain('title', 'content', 'status');
});

test('article belongs to user', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create(['user_id' => $user->id]);
    
    expect($article->user)->toBeInstanceOf(User::class);
    expect($article->user->id)->toBe($user->id);
});

test('article can be published', function () {
    $article = Article::factory()->create(['status' => 'draft']);
    
    $article->publish();
    
    expect($article->status)->toBe('published');
    expect($article->published_at)->not->toBeNull();
});

test('published scope returns only published articles', function () {
    Article::factory()->count(5)->create(['status' => 'published']);
    Article::factory()->count(3)->create(['status' => 'draft']);
    
    $published = Article::published()->get();
    
    expect($published)->toHaveCount(5);
});

test('article generates slug automatically', function () {
    $article = Article::factory()->create(['title' => 'Test Article Title']);
    
    expect($article->slug)->toBe('test-article-title');
});

test('article generates excerpt automatically', function () {
    $content = str_repeat('Lorem ipsum dolor sit amet. ', 100);
    $article = Article::factory()->create(['content' => $content]);
    
    expect($article->excerpt)->not->toBeNull();
    expect(strlen($article->excerpt))->toBeLessThanOrEqual(200);
});

test('article can have multiple categories', function () {
    $article = Article::factory()->create();
    $categories = Category::factory()->count(3)->create();
    
    $article->categories()->attach($categories->pluck('id'));
    
    expect($article->categories)->toHaveCount(3);
});

test('featured articles are returned by featured scope', function () {
    Article::factory()->count(2)->create(['featured' => true]);
    Article::factory()->count(3)->create(['featured' => false]);
    
    $featured = Article::featured()->get();
    
    expect($featured)->toHaveCount(2);
});

test('article can increment views', function () {
    $article = Article::factory()->create(['views' => 10]);
    
    $article->incrementViews();
    
    expect($article->fresh()->views)->toBe(11);
});

test('article search finds matching articles', function () {
    Article::factory()->create(['title' => 'Laravel Tutorial']);
    Article::factory()->create(['title' => 'PHP Best Practices']);
    Article::factory()->create(['title' => 'Laravel Tips']);
    
    $results = Article::search('Laravel')->get();
    
    expect($results)->toHaveCount(2);
});
```

### Step 4: Run Your First Tests

```bash
# Run all tests
./vendor/bin/pest

# Run specific test file
./vendor/bin/pest tests/Unit/Models/ArticleTest.php

# Run with coverage
./vendor/bin/pest --coverage

# Run with coverage minimum threshold
./vendor/bin/pest --coverage --min=70
```

---

## 📝 Writing More Tests

### Test Structure Template

**Unit Test Template:**
```php
<?php

use App\Modules\[Module]\Models\[Model];

describe('[Model] Model', function () {
    test('has correct fillable attributes', function () {
        // Test fillable
    });
    
    test('has correct relationships', function () {
        // Test relationships
    });
    
    test('scopes work correctly', function () {
        // Test scopes
    });
    
    test('accessors return correct values', function () {
        // Test accessors
    });
    
    test('methods perform expected actions', function () {
        // Test methods
    });
});
```

**Feature Test Template:**
```php
<?php

use App\Modules\User\Models\User;

describe('[Controller] Controller', function () {
    beforeEach(function () {
        $this->user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->user);
    });
    
    test('index returns correct view', function () {
        $response = $this->get(route('admin.articles.index'));
        
        $response->assertOk();
        $response->assertViewIs('admin.articles.index');
    });
    
    test('store creates new record', function () {
        $data = [
            'title' => 'Test Article',
            'content' => 'Test content',
        ];
        
        $response = $this->post(route('admin.articles.store'), $data);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('articles', ['title' => 'Test Article']);
    });
    
    test('unauthorized user cannot access', function () {
        Auth::logout();
        
        $response = $this->get(route('admin.articles.index'));
        
        $response->assertRedirect(route('admin.login'));
    });
});
```

---

## 🎯 Daily Development Workflow

### Morning Routine (9:00 AM)
```bash
# 1. Pull latest changes
git pull origin main

# 2. Update dependencies (if composer.lock changed)
composer install

# 3. Run migrations
php artisan migrate

# 4. Clear caches
php artisan config:clear
php artisan cache:clear

# 5. Run tests to ensure everything works
./vendor/bin/pest
```

### During Development
```bash
# 1. Create feature branch
git checkout -b feature/article-tests

# 2. Write tests first (TDD)
# Create test file, write test, see it fail

# 3. Write implementation
# Make the test pass

# 4. Run tests frequently
./vendor/bin/pest tests/Unit/Models/ArticleTest.php

# 5. Commit when tests pass
git add .
git commit -m "test(article): add unit tests for Article model"

# 6. Push to remote
git push origin feature/article-tests
```

### End of Day
```bash
# 1. Run full test suite
./vendor/bin/pest

# 2. Check coverage
./vendor/bin/pest --coverage

# 3. Commit any pending work
git add .
git commit -m "wip: article tests in progress"
git push

# 4. Update task tracker
# Mark completed tasks in GitHub Projects
```

---

## 📊 Test Coverage Goals

### Week 1 Targets
```
Day 1-2: Models (5 models × 10 tests = 50 tests)
├── Article: 10 tests ✓
├── Category: 10 tests
├── Page: 10 tests
├── User: 10 tests
└── Media: 10 tests

Day 3-4: Observers (6 observers × 5 tests = 30 tests)
├── ArticleObserver: 5 tests
├── CategoryObserver: 5 tests
├── PageObserver: 5 tests
├── MediaObserver: 5 tests
├── UserObserver: 5 tests
└── MenuObserver: 5 tests

Day 5-7: Requests (20 requests × 3 tests = 60 tests)
└── All validation tests

Total Week 1: 140 tests
Coverage Target: 40%
```

### Week 2 Targets
```
Day 1-3: Controllers (10 controllers × 8 tests = 80 tests)
├── ArticleController: 8 tests
├── CategoryController: 8 tests
├── PageController: 8 tests
├── MediaController: 8 tests
├── UserController: 8 tests
└── Others: 40 tests

Day 4-5: Integration (20 tests)
└── Complete workflows

Day 6-7: Browser Tests (10 tests)
└── Dusk tests

Total Week 2: 110 tests
Total Sprint 1: 250+ tests
Coverage Target: 70%
```

---

## 🛠️ Useful Commands

### Testing
```bash
# Run all tests
./vendor/bin/pest

# Run specific suite
./vendor/bin/pest --testsuite=Unit
./vendor/bin/pest --testsuite=Feature

# Run specific file
./vendor/bin/pest tests/Unit/Models/ArticleTest.php

# Run specific test
./vendor/bin/pest --filter="article generates slug automatically"

# Watch mode (auto-run on file change)
./vendor/bin/pest --watch

# Parallel testing (faster)
./vendor/bin/pest --parallel

# Coverage report
./vendor/bin/pest --coverage --min=70

# Coverage HTML report
./vendor/bin/pest --coverage-html coverage-report
```

### Database
```bash
# Fresh migrate
php artisan migrate:fresh

# Fresh migrate with seeds
php artisan migrate:fresh --seed

# Run specific seeder
php artisan db:seed --class=ArticleSeeder

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset
```

### Cache
```bash
# Clear all caches
php artisan optimize:clear

# Or individually
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Development Server
```bash
# Start server
php artisan serve

# Start with specific port
php artisan serve --port=8080

# Start Vite dev server (separate terminal)
npm run dev

# Build assets for production
npm run build
```

---

## 🐛 Debugging Tips

### Enable Debug Mode
```bash
# .env
APP_DEBUG=true
APP_ENV=local
```

### Use Laravel Telescope
```bash
# Install
composer require laravel/telescope --dev

# Publish
php artisan telescope:install

# Migrate
php artisan migrate

# Access: http://localhost/telescope
```

### Dump & Die Helpers
```php
// In your code
dd($variable); // Dump and die
dump($variable); // Dump and continue
ddd($variable); // Dump, die, and debug

// In Blade
@dd($variable)
@dump($variable)
```

### Test Debugging
```php
test('debugging example', function () {
    $article = Article::factory()->create();
    
    // Use dump to see values
    dump($article->toArray());
    
    // Or ray (better debugging tool)
    ray($article);
    
    expect($article->title)->toBe('Expected Title');
});
```

---

## 📚 Resources

### Documentation
- Laravel 12: https://laravel.com/docs/12.x
- Pest PHP: https://pestphp.com/docs
- Tailwind CSS v4: https://tailwindcss.com/docs
- TinyMCE: https://www.tiny.cloud/docs/

### Learning
- Laracasts: https://laracasts.com
- Laravel News: https://laravel-news.com
- Laravel Daily: https://laraveldaily.com

### Community
- Laravel Discord: https://discord.gg/laravel
- Reddit r/laravel: https://reddit.com/r/laravel
- Stack Overflow: [laravel] tag

---

## ✅ Sprint 1 Checklist

### Week 1: Unit Tests
- [ ] Setup Pest PHP
- [ ] Configure phpunit.xml
- [ ] Article model tests (10)
- [ ] Category model tests (10)
- [ ] Page model tests (10)
- [ ] User model tests (10)
- [ ] Media model tests (10)
- [ ] Menu models tests (10)
- [ ] Observer tests (30)
- [ ] Request validation tests (60)
- [ ] Coverage: 40%+

### Week 2: Feature Tests
- [ ] ArticleController tests (8)
- [ ] CategoryController tests (8)
- [ ] PageController tests (8)
- [ ] MediaController tests (8)
- [ ] UserController tests (8)
- [ ] Other controllers tests (40)
- [ ] Integration workflow tests (20)
- [ ] Browser tests with Dusk (10)
- [ ] Coverage: 70%+
- [ ] CI/CD pipeline setup

---

## 🎉 Success Criteria

### Definition of Done
- ✅ All tests written and passing
- ✅ Test coverage ≥ 70%
- ✅ No failing tests
- ✅ CI/CD pipeline green
- ✅ Code reviewed and merged
- ✅ Documentation updated

### Code Quality Standards
- ✅ PSR-12 coding standard
- ✅ No unused imports
- ✅ Proper type hints
- ✅ Meaningful variable names
- ✅ Comments for complex logic
- ✅ No hardcoded values

---

## 🚀 Let's Start!

### First Task (Right Now)
```bash
# 1. Install Pest
composer require pestphp/pest --dev --with-all-dependencies
composer require pestphp/pest-plugin-laravel --dev

# 2. Initialize Pest
./vendor/bin/pest --init

# 3. Create first test
mkdir -p tests/Unit/Models
touch tests/Unit/Models/ArticleTest.php

# 4. Copy the ArticleTest code from above

# 5. Run it!
./vendor/bin/pest tests/Unit/Models/ArticleTest.php
```

### What's Next
1. ✅ Complete Article model tests
2. Move to Category model tests
3. Continue with other models
4. Complete Week 1 targets
5. Start Week 2 feature tests

---

**Ready to code?** 🚀

**Questions?** Check documentation or ask in Discord!

**Let's build JA-CMS v2.0!** 💪
