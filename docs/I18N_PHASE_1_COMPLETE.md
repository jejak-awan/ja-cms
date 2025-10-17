> DEPRECATED: This document contains references to legacy paths (e.g., app/Support/Translatable.php, app/Services/LocaleService.php). Refer to RESUME_STATUS.md for updated module paths and current implementation.

# 🌐 JA-CMS Internationalization (i18n) - Implementation Guide

**Date**: October 14, 2025  
**Status**: ✅ Phase 1 COMPLETED  
**Duration**: 7 Days (Planned)

---

## 📊 Project Overview

JA-CMS now supports **Multi-Language (i18n)** with a flexible, scalable architecture:
- **2 Languages**: Indonesian (ID) 🇮🇩 & English (EN) 🇬🇧
- **Translatable Content**: Articles, Categories, Pages
- **SEO-Optimized**: Hreflang tags, localized URLs
- **Auto-Detection**: Browser language, session, cookie
- **Admin-Friendly**: Translation UI with tabs

---

## ✅ Phase 1: Database & Config Setup (COMPLETED)

### 1.1 Database Schema

#### Languages Table
```sql
CREATE TABLE languages (
    id BIGINT PRIMARY KEY,
    code VARCHAR(10) UNIQUE,        -- 'id', 'en'
    name VARCHAR(100),               -- 'Indonesian', 'English'
    native_name VARCHAR(100),        -- 'Bahasa Indonesia', 'English'
    flag VARCHAR(10),                -- '🇮🇩', '🇬🇧'
    direction ENUM('ltr', 'rtl'),    -- Text direction
    is_active BOOLEAN DEFAULT TRUE,
    is_default BOOLEAN DEFAULT FALSE,
    order INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### Translatable Columns (Articles Example)
```sql
-- Before (single language):
articles: id, title, excerpt, content, slug, ...

-- After (multi-language):
articles: 
    id, 
    locale VARCHAR(10) DEFAULT 'id',
    title_id, title_en,              -- Title in ID & EN
    excerpt_id, excerpt_en,          -- Excerpt in ID & EN
    content_id, content_en,          -- Content in ID & EN
    slug,
    ...
```

Same pattern applied to:
- ✅ **categories**: name_id, name_en, description_id, description_en
- ✅ **pages**: title_id, title_en, content_id, content_en

### 1.2 Configuration Files

#### `config/locales.php` (NEW)
```php
return [
    'supported' => [
        'id' => [
            'name' => 'Indonesian',
            'native' => 'Bahasa Indonesia',
            'flag' => '🇮🇩',
            'direction' => 'ltr',
        ],
        'en' => [
            'name' => 'English',
            'native' => 'English',
            'flag' => '🇬🇧',
            'direction' => 'ltr',
        ],
    ],
    'default' => env('APP_LOCALE', 'id'),
    'fallback' => env('APP_FALLBACK_LOCALE', 'en'),
    'hide_default_in_url' => true,  // /artikel (not /id/artikel)
    'use_session' => true,
    'use_cookie' => true,
    'translatable_models' => [
        'Article' => ['fields' => ['title', 'excerpt', 'content']],
        'Category' => ['fields' => ['name', 'description']],
        'Page' => ['fields' => ['title', 'content']],
    ],
];
```

#### Updated `.env`
```env
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID
```

### 1.3 Models & Factories

#### Language Model
```php
// app/Modules/Language/Models/Language.php
class Language extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code', 'name', 'native_name', 'flag', 
        'direction', 'is_active', 'is_default', 'order'
    ];
    
    public static function active() { ... }
    public static function default() { ... }
    public static function byCode(string $code) { ... }
}
```

#### Translatable Trait
```php
// app/Support/Translatable.php
trait Translatable
{
    public function trans(string $key, ?string $locale = null)
    {
        // Returns $this->title_en or $this->title_id
        // With automatic fallback to default locale
    }
    
    public function getTranslations(string $key): array
    {
        // Returns ['id' => '...', 'en' => '...']
    }
    
    public function setTranslation(string $key, $value, ?string $locale) { ... }
}
```

**Usage Example:**
```php
use App\Support\Translatable;

class Article extends Model
{
    use Translatable;
    
    protected $translatable = ['title', 'excerpt', 'content'];
}

// Auto-magic accessor:
$article->title;                    // Returns title_en or title_id (based on current locale)
$article->trans('title', 'en');     // Explicitly get English title
$article->getTranslations('title'); // Get all translations
```

### 1.4 Middleware

#### SetLocale Middleware
```php
// app/Http/Middleware/SetLocale.php
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Detection priority:
        // 1. URL segment (/en/articles)
        // 2. Query param (?lang=en)
        // 3. Session
        // 4. Cookie
        // 5. Browser Accept-Language header
        // 6. Default from config
        
        $locale = $this->detectLocale($request);
        App::setLocale($locale);
        Session::put('locale', $locale);
        Cookie::queue('locale', $locale, 365 * 24 * 60);
        
        return $next($request);
    }
}
```

**Registered in** `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->web(append: [
        \App\Http\Middleware\SetLocale::class,
    ]);
    
    $middleware->alias([
        'locale' => \App\Http\Middleware\SetLocale::class,
    ]);
})
```

### 1.5 Services

#### LocaleService
```php
// app/Services/LocaleService.php
class LocaleService
{
    public static function active(): Collection                // Get active languages
    public static function default(): ?Language                // Get default language
    public static function byCode(string $code): ?Language     // Get by code
    public static function current(): string                   // Get current locale
    public static function set(string $locale): void           // Set locale
    public static function isSupported(string $locale): bool   // Check if supported
    public static function route(string $name, array $params)  // Localized routes
    public static function alternateUrls(...): array           // For hreflang tags
    public static function clearCache(): void                  // Clear cache
}
```

**Usage:**
```php
use App\Services\LocaleService;

// Get all active languages
$languages = LocaleService::active(); // Cached for 1 hour

// Check current locale
$current = LocaleService::current(); // 'id' or 'en'

// Generate localized URL
$url = LocaleService::route('articles.show', ['article' => $article, 'locale' => 'en']);
// Result: /en/articles/my-article (or /articles/my-article if default locale)

// Get alternate URLs for SEO
$alternates = LocaleService::alternateUrls('articles.show', ['article' => $article]);
// Result: ['id' => '/artikel/...', 'en' => '/en/articles/...']
```

### 1.6 Database Seeder

```php
// database/seeders/LanguageSeeder.php
class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        Language::updateOrCreate(['code' => 'id'], [
            'name' => 'Indonesian',
            'native_name' => 'Bahasa Indonesia',
            'flag' => '🇮🇩',
            'is_default' => true,
            'is_active' => true,
            'order' => 1,
        ]);
        
        Language::updateOrCreate(['code' => 'en'], [
            'name' => 'English',
            'native_name' => 'English',
            'flag' => '🇬🇧',
            'is_active' => true,
            'order' => 2,
        ]);
    }
}
```

**Registered in** `database/seeders/DatabaseSeeder.php`:
```php
$this->call([
    LanguageSeeder::class,
    CategorySeeder::class,
    ArticleSeeder::class,
]);
```

---

## 🎯 Phase 1 Deliverables (✅ COMPLETED)

| Item | Status |
|------|--------|
| Languages table migration | ✅ Done |
| Locale columns for Articles | ✅ Done |
| Locale columns for Categories | ✅ Done |
| Locale columns for Pages | ✅ Done |
| Language model + factory | ✅ Done |
| Translatable trait | ✅ Done |
| SetLocale middleware | ✅ Done |
| LocaleService helper | ✅ Done |
| config/locales.php | ✅ Done |
| LanguageSeeder | ✅ Done |
| Database seeded (ID + EN) | ✅ Done |

---

## 📝 Next Steps (Phase 2)

### Day 2: Translation Files & Helpers
- [ ] Create `lang/id/` directory structure
- [ ] Create `lang/en/` directory structure
- [ ] Translate Laravel defaults (validation, auth, pagination)
- [ ] Translate custom messages (admin UI, errors, success)
- [ ] Create helper functions for easy translation
- [ ] Update existing views to use `__()` helpers

**Files to Create:**
```
resources/lang/
├── id/
│   ├── auth.php
│   ├── validation.php
│   ├── pagination.php
│   ├── messages.php
│   └── admin.php
└── en/
    ├── auth.php
    ├── validation.php
    ├── pagination.php
    ├── messages.php
    └── admin.php
```

---

## 🏗️ Architecture Benefits

### 1. **Scalable**
- Easy to add new languages (just add columns: `title_fr`, `title_de`)
- No separate translation tables needed
- No complex joins

### 2. **Performance**
- Single query per model (no eager loading hassles)
- Cached language list (1 hour TTL)
- Locale detection cached in session/cookie

### 3. **SEO-Friendly**
- Clean URLs: `/artikel/judul-artikel` or `/en/articles/article-title`
- Hreflang tags support
- Language-specific slugs possible

### 4. **Developer-Friendly**
- Magic accessor: `$article->title` works automatically
- Explicit access: `$article->trans('title', 'en')`
- Easy fallback mechanism

### 5. **Admin-Friendly**
- Translation UI with tabs (coming in Phase 3)
- Visual language switcher
- See all translations at once

---

## 🔧 Migration Commands

```bash
# Run migrations
php artisan migrate

# Seed languages
php artisan db:seed --class=LanguageSeeder

# Rollback (if needed)
php artisan migrate:rollback --step=4

# Fresh migration + seed
php artisan migrate:fresh --seed
```

---

## 📚 Usage Examples

### Example 1: Get Translated Content
```php
// Automatically uses current locale
$article = Article::find(1);
echo $article->title;        // Returns title_id if locale is 'id'
echo $article->excerpt;      // Returns excerpt_en if locale is 'en'

// Explicit locale
echo $article->trans('title', 'en');    // English title
echo $article->trans('content', 'id');  // Indonesian content
```

### Example 2: Set Translations
```php
$article = new Article();
$article->setTranslation('title', 'My Article', 'en');
$article->setTranslation('title', 'Artikel Saya', 'id');
$article->save();
```

### Example 3: Get All Translations
```php
$article = Article::find(1);
$titles = $article->getTranslations('title');
// Result: ['id' => 'Artikel Saya', 'en' => 'My Article']
```

### Example 4: Language Switcher (Blade)
```blade
@foreach(App\Services\LocaleService::active() as $language)
    <a href="{{ LocaleService::route(Route::currentRouteName(), ['locale' => $language->code]) }}">
        {{ $language->flag }} {{ $language->native_name }}
    </a>
@endforeach
```

---

## 🎊 Phase 1 Summary

**Status**: ✅ **SUCCESSFULLY COMPLETED**

We've built a **solid foundation** for multi-language support:
- ✅ Database schema ready for translations
- ✅ Automatic locale detection via middleware
- ✅ Elegant API with Translatable trait
- ✅ Helper services for common tasks
- ✅ Cached for performance
- ✅ 2 languages seeded and working

**Next**: Phase 2 will focus on creating actual translation files for the UI! 🚀

---

**Report Generated**: October 14, 2025  
**Author**: GitHub Copilot  
**Project**: JA-CMS i18n Implementation  
**Status**: ✅ Phase 1 COMPLETE - Ready for Phase 2
