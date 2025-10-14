# 🚀 Phase 3 Quick Reference Guide

> **Quick guide untuk menggunakan i18n Admin Panel features**

---

## 📦 Components

### 1. Language Switcher Component

```blade
{{-- Basic usage --}}
<x-language-switcher />

{{-- With options --}}
<x-language-switcher 
    position="bottom-right"  {{-- bottom-right, bottom-left, top-right, top-left --}}
    size="md"                {{-- sm, md, lg --}}
    :showLabel="true"        {{-- Show language name --}}
/>
```

---

## 🔧 Custom Blade Directives

### Translation

```blade
@t('messages.welcome')              {{-- Short translation --}}
@te('admin.title')                  {{-- Escaped translation --}}
@traw('messages.html')              {{-- Raw (unescaped) --}}
@tchoice('messages.items', 5)       {{-- Pluralization --}}
@tfield($article, 'title')          {{-- Translated model field --}}
```

### Locale Information

```blade
@locale                             {{-- Current locale code: id --}}
@localeName                         {{-- Current locale name: Bahasa Indonesia --}}
@localeName('en')                   {{-- Specific locale name: English --}}
@localeFlag                         {{-- Current flag: 🇮🇩 --}}
@localeFlag('en')                   {{-- Specific flag: 🇬🇧 --}}
@localeDir                          {{-- Text direction: ltr or rtl --}}
@formatDate($date)                  {{-- Format date with locale --}}
```

### Conditionals

```blade
@locale('id')
    <p>Indonesian content</p>
@endlocale

@rtl
    <div class="text-right">RTL content</div>
@endrtl

@ltr
    <div class="text-left">LTR content</div>
@endltr

@multilingual
    <x-language-switcher />
@endmultilingual
```

---

## 💻 Helper Functions

```php
// Get current locale
current_locale()              // Returns: 'id' or 'en'

// Set locale
set_locale('id')              // Set to Indonesian
App::setLocale('en')          // Laravel way

// Check locale
is_locale('id')               // Returns: true/false

// Get locale info
locale_name()                 // Current: 'Bahasa Indonesia'
locale_name('en')             // Specific: 'English'
locale_flag()                 // Current: '🇮🇩'
locale_flag('en')             // Specific: '🇬🇧'

// Get available languages
active_languages()            // Collection of active Language models (ordered)

// Check text direction
is_rtl()                      // Returns: true/false for RTL languages

// Format date
format_date_locale($date)     // Formats with current locale
```

---

## 🛣️ Routes

```php
// Switch language (POST)
route('locale.switch', 'id')   // /locale/id

// Get current locale info (GET - JSON)
route('locale.current')        // /api/locale/current
```

---

## 📝 In Controllers

```php
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\App;

// Switch locale programmatically
public function changeLanguage($locale)
{
    set_locale($locale);
    
    // Or
    App::setLocale($locale);
    
    return redirect()->back();
}

// Get translated field
public function show($id)
{
    $article = Article::find($id);
    $title = trans_field($article, 'title');
    
    return view('article.show', compact('article', 'title'));
}
```

---

## 🎨 In JavaScript

```javascript
// Get current locale via API
fetch('/api/locale/current')
    .then(response => response.json())
    .then(data => {
        console.log('Current locale:', data.locale);
        console.log('Locale name:', data.name);
        console.log('Available:', data.available_locales);
    });

// Switch language via form
const switchLocale = (locale) => {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/locale/${locale}`;
    
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = document.querySelector('meta[name="csrf-token"]').content;
    
    form.appendChild(csrf);
    document.body.appendChild(form);
    form.submit();
};

// Usage
switchLocale('id'); // Switch to Indonesian
```

---

## 🌐 Translation Files

### Structure

```
lang/
├── id/
│   ├── admin.php       # Admin panel translations
│   ├── messages.php    # General messages
│   ├── auth.php        # Authentication
│   ├── validation.php  # Validation messages
│   └── pagination.php  # Pagination
└── en/
    ├── admin.php
    ├── messages.php
    ├── auth.php
    ├── validation.php
    └── pagination.php
```

### Usage

```php
// In PHP/Blade
__('messages.welcome')              // Basic
__('messages.greeting', ['name' => 'John'])  // With parameters
trans('admin.nav.dashboard')        // Alternative
trans_choice('messages.items', 5)   // Pluralization

// In Blade (short syntax)
@t('messages.welcome')
@te('messages.title')
```

---

## 🔑 Key Translation Keys

### Admin Panel (`admin.php`)

```php
// Sidebar
'admin.nav.dashboard'
'admin.nav.articles'
'admin.nav.categories'
'admin.nav.users'

// Language Switcher
'admin.switch_language'
'admin.current_locale'
'admin.select_language'
'admin.language_switched'

// Articles
'admin.articles.title'
'admin.articles.create'
'admin.articles.edit'
'admin.articles.list'
```

### General Messages (`messages.php`)

```php
'messages.welcome'
'messages.subtitle'
'messages.success'
'messages.error'
'messages.save'
'messages.cancel'
'messages.delete'
'messages.locale_switched'
'messages.invalid_locale'
```

---

## 🎯 Common Patterns

### 1. Form with Translations

```blade
<form method="POST" action="{{ route('articles.store') }}">
    @csrf
    
    <label>@t('admin.articles.title_label')</label>
    <input type="text" name="title" placeholder="@t('admin.articles.title_label')">
    
    <label>@t('admin.articles.content_label')</label>
    <textarea name="content"></textarea>
    
    <button type="submit">@t('messages.save')</button>
</form>
```

### 2. Success/Error Messages

```blade
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif
```

### 3. Multilingual Content

```blade
@locale('id')
    <h1>{{ $article->title_id }}</h1>
    <div>{!! $article->content_id !!}</div>
@else
    <h1>{{ $article->title }}</h1>
    <div>{!! $article->content !!}</div>
@endlocale
```

### 4. Language Switcher in Navbar

```blade
<nav class="flex items-center justify-between">
    <div class="logo">
        <a href="/">My Site</a>
    </div>
    
    <div class="flex items-center space-x-4">
        <a href="/">@t('messages.home')</a>
        <a href="/articles">@t('admin.nav.articles')</a>
        
        <x-language-switcher size="sm" :showLabel="false" />
    </div>
</nav>
```

---

## 📱 Responsive Usage

```blade
{{-- Mobile: Flag only --}}
<div class="md:hidden">
    <x-language-switcher :showLabel="false" size="sm" />
</div>

{{-- Desktop: Flag + Label --}}
<div class="hidden md:block">
    <x-language-switcher :showLabel="true" size="md" />
</div>
```

---

## 🧪 Testing

### Manual Test

1. Visit admin panel: `/admin`
2. Click language switcher (flag icon)
3. Select different language
4. Verify interface updates

### Demo Page

Visit: `/i18n-demo`

Shows:
- Current locale info
- All available languages
- Blade directives in action
- Conditional rendering
- Date formatting

---

## 🐛 Troubleshooting

### Language not switching?

```bash
# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Translations not showing?

```php
// Check if translation key exists
dd(__('messages.welcome'));

// Check current locale
dd(current_locale());

// Check available languages
dd(active_languages());
```

### Component not rendering?

```bash
# Make sure Alpine.js is loaded
# Check browser console for errors

# Clear view cache
php artisan view:clear
```

---

## 📚 Documentation

- Full Guide: `docs/I18N_PHASE_3_COMPLETE.md`
- Helper Functions: `docs/I18N_PHASE_2_COMPLETE.md`
- Configuration: `docs/I18N_PHASE_1_COMPLETE.md`

---

**Need help?** Check the demo page at `/i18n-demo` 🚀
