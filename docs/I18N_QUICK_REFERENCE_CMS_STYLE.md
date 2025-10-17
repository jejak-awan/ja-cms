# 🚀 I18n Quick Reference - CMS-Style Translation
## Cheat Sheet untuk Developer

---

## 📌 **Quick Function Reference**

### **WordPress-Style Functions**

| Function | Purpose | Example | Output |
|----------|---------|---------|--------|
| `_t()` | Translate with domain | `_t('save', 'admin')` | "Save" |
| `_e()` | Echo translation | `_e('title', 'page')` | Echoes "Title" |
| `_n()` | Plural forms | `_n('1 item', ':count items', 5)` | "5 items" |
| `_x()` | With context | `_x('Post', 'noun', 'admin')` | "Article" |
| `esc_html__()` | Escaped HTML | `esc_html__('title', 'admin')` | HTML-safe |
| `esc_attr__()` | Escaped attribute | `esc_attr__('placeholder')` | Attr-safe |

### **Laravel Native (Still Works)**

| Function | Purpose | Example |
|----------|---------|---------|
| `__()` | Translate | `__('messages.welcome')` |
| `trans()` | Translate | `trans('admin.title')` |
| `trans_choice()` | Plural | `trans_choice('messages.items', 5)` |
| `@lang()` | Blade directive | `@lang('messages.hello')` |

### **Joomla-Style**

| Function | Purpose | Example |
|----------|---------|---------|
| `JText()` | Translate | `JText('COM_ADMIN_SAVE')` |

### **Translation Management**

| Function | Purpose | Example |
|----------|---------|---------|
| `trans_domain()` | Preload domain | `trans_domain('admin')` |
| `trans_missing()` | Get missing | `trans_missing()` |
| `trans_stats()` | Get statistics | `trans_stats()` |
| `trans_export_js()` | Export to JS | `trans_export_js(['admin'])` |
| `trans_clear_cache()` | Clear cache | `trans_clear_cache('admin')` |

---

## 💻 **Code Examples**

### **1. Basic Translation**

```php
// WordPress-style with domain
_t('welcome', 'messages')
// → "Welcome"

// With parameters
_t('hello_name', 'messages', ['name' => 'John'])
// → "Hello, John!"

// Laravel way (still works)
__('messages.welcome')
// → "Welcome"
```

### **2. In Blade Templates**

```blade
{{-- Simple translation --}}
<h1>{{ _t('title', 'page') }}</h1>

{{-- With parameters --}}
<p>{{ _t('welcome_user', 'messages', ['name' => $user->name]) }}</p>

{{-- Escaped for HTML --}}
<title>{{ esc_html__('page_title', 'seo') }}</title>

{{-- For input attributes --}}
<input placeholder="{{ esc_attr__('search', 'form') }}">

{{-- Pluralization --}}
<span>{{ _n('1 comment', ':count comments', $count, 'blog') }}</span>

{{-- Using Blade directive --}}
@t('messages.hello')
```

### **3. In Controllers**

```php
use App\Services\TranslationService;

class ArticleController extends Controller
{
    public function index()
    {
        // Preload translations for performance
        trans_domain(['admin', 'article-articles']);
        
        // Use in messages
        return redirect()
            ->back()
            ->with('success', _t('article_saved', 'admin'));
    }
    
    public function create()
    {
        // Set page title with translation
        $title = _t('create_article', 'admin');
        
        return view('admin.articles.create', compact('title'));
    }
}
```

### **4. Database Overrides (Joomla-Style)**

```php
use App\Services\TranslationService;

// Set override
TranslationService::setOverride(
    'welcome_message',           // key
    'Selamat datang!',          // new value
    'frontend',                  // domain
    'id'                        // locale
);

// Now this returns override:
_t('welcome_message', 'frontend')
// → "Selamat datang!" (instead of original)

// Remove override
TranslationService::removeOverride('welcome_message', 'frontend', 'id');
```

### **5. JavaScript Integration**

```blade
{{-- In your layout --}}
<script>
// Export translations to JavaScript
window.i18n = {!! trans_export_js(['messages', 'validation', 'admin']) !!};

// Usage in JS
console.log(window.i18n.messages.welcome);
console.log(window.i18n.validation.required);

// With function wrapper
function trans(domain, key) {
    return window.i18n[domain]?.[key] || key;
}

alert(trans('messages', 'welcome'));
</script>
```

### **6. Translation Statistics**

```php
// Get completion stats
$stats = trans_stats();

/*
[
    'id' => [
        'locale' => 'id',
        'name' => 'Bahasa Indonesia',
        'total_strings' => 1250,
        'completion' => 100.0,
        'is_default' => true
    ],
    'en' => [
        'locale' => 'en',
        'name' => 'English',
        'total_strings' => 875,
        'completion' => 70.0,
        'is_default' => false
    ]
]
*/
```

### **7. Missing Translation Detection**

```php
// In debug mode, missing translations are tracked
$missing = trans_missing();

/*
[
    'admin:save_button:id' => [
        'key' => 'save_button',
        'domain' => 'admin',
        'locale' => 'id',
        'count' => 15,
        'first_seen' => Carbon instance
    ]
]
*/

// Check logs
// storage/logs/laravel.log
// "Missing translation: admin.save_button for locale id"
```

---

## 📁 **File Structure**

```
lang/
├── en/
│   ├── admin.php              ← Domain: 'admin'
│   ├── messages.php           ← Domain: 'messages'
│   ├── frontend.php           ← Domain: 'frontend'
│   ├── validation.php         ← Domain: 'validation'
│   └── auth.php               ← Domain: 'auth'
└── id/
    └── (same structure)

app/Modules/Article/
└── lang/
    ├── en/
    │   └── articles.php       ← Domain: 'article-articles'
    └── id/
        └── articles.php
```

---

## 🎨 **Domain Naming**

```php
// Core domains
'admin'       → Admin panel translations
'frontend'    → Public-facing website
'messages'    → General messages
'validation'  → Form validation
'auth'        → Authentication

// Module domains (format: module-file)
'article-articles'   → Article module
'user-profile'       → User profile module
'media-library'      → Media library module
```

---

## ⚡ **Performance Tips**

```php
// 1. Preload critical domains
trans_domain(['admin', 'messages', 'validation']);

// 2. Load in AppServiceProvider
public function boot()
{
    TranslationService::preload(['admin', 'validation']);
}

// 3. Clear cache in production
trans_clear_cache();  // After deployment

// 4. Export only needed translations to JS
trans_export_js(['messages', 'validation']);
// Don't export everything!
```

---

## 🔧 **Configuration**

### **config/locales.php**

```php
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

'default' => 'id',
'fallback' => 'en',
'use_cache' => true,
'cache_duration' => 60, // minutes
```

---

## 🐛 **Debugging**

```php
// Enable debug mode
// .env: APP_DEBUG=true

// Check missing translations
dd(trans_missing());

// View statistics
dd(trans_stats());

// Clear translation cache
trans_clear_cache();

// Clear specific domain
trans_clear_cache('admin');

// Clear domain + locale
trans_clear_cache('admin', 'id');
```

---

## 📋 **Common Patterns**

### **Pattern 1: Admin Panel**

```php
// Controller
public function __construct()
{
    trans_domain('admin');
}

// View
<h1>{{ _t('dashboard_title', 'admin') }}</h1>
<button>{{ _t('save', 'admin') }}</button>
```

### **Pattern 2: Form Validation**

```php
// Controller
$request->validate([
    'email' => 'required|email',
], [
    'email.required' => _t('email_required', 'validation'),
    'email.email' => _t('email_invalid', 'validation'),
]);
```

### **Pattern 3: Success/Error Messages**

```php
return redirect()
    ->back()
    ->with('success', _t('saved_successfully', 'messages'));
    
return back()
    ->withErrors(['error' => _t('save_failed', 'messages')]);
```

### **Pattern 4: Contextual Translation**

```php
// Noun vs Verb
$postNoun = _x('Post', 'noun', 'admin');    // "Artikel"
$postVerb = _x('Post', 'verb', 'admin');    // "Kirim"

// File lang/id/admin.php
[
    'Post_noun' => 'Artikel',
    'Post_verb' => 'Kirim',
]
```

---

## ✅ **Best Practices Checklist**

- ✅ Use text domains for organization
- ✅ Name keys descriptively (snake_case)
- ✅ Escape output when needed (esc_html__, esc_attr__)
- ✅ Preload critical domains for performance
- ✅ Use overrides for client-specific changes
- ✅ Track missing translations in development
- ✅ Clear cache after deployment
- ✅ Export minimal translations to JavaScript
- ✅ Test all supported locales
- ✅ Document translation context

---

## 🔗 **Related Documentation**

- [I18N Best Practices - Full Guide](./I18N_BEST_PRACTICES_CMS_COMPARISON.md)
- [Phase 3 Implementation](./I18N_PHASE_3_COMPLETE.md)
- [Translation API](./API_DOCUMENTATION.md)

---

**Quick Start**: `_t('key', 'domain')` → That's it! 🚀
