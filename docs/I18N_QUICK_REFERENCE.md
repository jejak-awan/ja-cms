# I18N Quick Reference Guide

## 🚀 Quick Start

### For Developers

#### 1. Using Translations in Code
```php
// Simple translation
__('welcome')                           // From messages.php
__('validation.required')               // From validation.php

// With parameters
__('welcome.user', ['name' => 'John'])  // Welcome, John!

// Using TranslationService directly
use App\Modules\Language\Services\TranslationService;

TranslationService::trans('welcome', [], 'messages', 'en');
```

#### 2. Using Translations in Blade
```blade
{{-- Simple --}}
{{ __('welcome') }}

{{-- With domain --}}
{{ __('override.message', [], 'admin') }}

{{-- In attributes --}}
<input placeholder="{{ __('search') }}">
```

#### 3. Using Translations in JavaScript
```blade
{{-- Include component in layout --}}
<x-translations-js domain="messages" />

{{-- Then use in JS --}}
<script>
    console.log(trans.welcome);         // Access translations
    console.log(__('welcome'));         // Helper function
</script>
```

#### 4. Switch Language
```php
// In controller
return redirect()->route('locale.switch', ['locale' => 'id']);

// Or via LocaleService
use App\Modules\Language\Services\LocaleService;

LocaleService::switch('id');
```

---

## 🎨 For Admins

### Access Admin Panel
```
http://your-site.com/admin/translations
```

### Quick Actions
- **View All:** `/admin/translations` - See all overrides
- **Create:** Click "Add Override" button
- **Statistics:** Click "Statistics" button - See coverage
- **Missing:** Click "Missing" button - Find untranslated keys
- **Clear Cache:** Button at bottom - Clear translation cache

### Adding New Translation Override
1. Click "Add Override"
2. Select language (e.g., Bahasa Indonesia)
3. Select domain (e.g., messages)
4. Enter key (e.g., `welcome.message`)
5. Enter translated text
6. Check "Active" checkbox
7. Click "Create Override"

### Editing Existing Override
1. Find override in list
2. Click "Edit" button
3. Modify translation value
4. Click "Update Override"

### Finding Missing Translations
1. Click "Missing" button
2. Filter by language/domain if needed
3. Click "Add" next to any missing key
4. Form will be pre-filled
5. Enter translation and save

---

## 🛣️ API Endpoints

### Get Translations (JSON)
```bash
# Get all messages translations for current locale
GET /api/translations/messages

# Response:
{
  "welcome": "Welcome",
  "dashboard": "Dashboard",
  "home": "Home"
}
```

### Switch Locale
```bash
# Switch to Indonesian
POST /locale/id
Headers: X-CSRF-TOKEN: {token}

# Redirects back with new locale
```

---

## 🔧 Configuration

### Supported Languages
Edit `config/locales.php`:
```php
'available' => [
    'en' => ['name' => 'English', 'native' => 'English'],
    'id' => ['name' => 'Indonesian', 'native' => 'Bahasa Indonesia'],
],

'default' => 'en',
```

### Cache Settings
Translation caching uses Laravel's default cache driver.

Clear cache:
```bash
php artisan cache:clear
# OR via admin: /admin/translations → "Clear Cache"
```

---

## 📁 File Locations

### Translation Files
```
lang/
├── en/
│   ├── messages.php      # General translations
│   ├── validation.php    # Validation messages
│   ├── auth.php          # Auth messages
│   └── ...
└── id/
    ├── messages.php
    ├── validation.php
    └── ...
```

### Module Files
```
app/Modules/Language/
├── Controllers/          # Admin & locale controllers
├── Services/             # Translation & locale services
├── Models/              # Language & override models
├── Middleware/          # Locale detection & setting
├── Traits/              # Translatable trait
└── Support/             # Route macros
```

---

## 🎯 Common Tasks

### Add New Language File
1. Create folder: `lang/id/` (for Indonesian)
2. Copy from English: `cp lang/en/messages.php lang/id/messages.php`
3. Translate strings in the new file
4. Add to `config/locales.php` available list

### Create Translation Override (Code)
```php
use App\Modules\Language\Models\LanguageOverride;

LanguageOverride::create([
    'locale' => 'id',
    'domain' => 'messages',
    'key' => 'custom.greeting',
    'value' => 'Halo, selamat datang!',
    'is_active' => true,
]);
```

### Get Translation Statistics
```php
use App\Modules\Language\Services\TranslationService;

$stats = TranslationService::getStatistics();

echo $stats['total_keys'];           // Total translation keys
echo $stats['total_overrides'];      // Database overrides count
echo $stats['coverage_percent'];     // Overall coverage %
print_r($stats['by_locale']);        // Per language stats
print_r($stats['by_domain']);        // Per domain stats
```

### Detect Missing Translations
```php
use App\Modules\Language\Services\TranslationService;

$missing = TranslationService::getMissingTranslations();

// Returns: ['id' => ['messages' => ['key1', 'key2']], ...]
foreach ($missing as $locale => $domains) {
    foreach ($domains as $domain => $keys) {
        echo "Missing in $locale / $domain: " . count($keys) . " keys\n";
    }
}
```

### Clear Translation Cache
```php
use App\Modules\Language\Services\TranslationService;

// Clear specific domain for specific locale
TranslationService::clearCache('messages', 'en');

// Clear all for a domain
TranslationService::clearCache('messages');

// Clear all for a locale
TranslationService::clearCache(null, 'en');

// Clear everything
TranslationService::clearCache();
```

---

## 🧩 Alpine.js Language Switcher

### Implementation
```blade
{{-- In your layout --}}
<div x-data="languageSwitcher()" class="relative">
    {{-- Dropdown button --}}
    <button @click="toggle" class="btn">
        {{ app()->getLocale() }}
    </button>
    
    {{-- Dropdown menu --}}
    <div x-show="open" @click.away="close" class="dropdown-menu">
        @foreach(app('locales')->available() as $locale)
            <a href="#" @click.prevent="switchLanguage('{{ $locale->code }}')">
                {{ $locale->native_name }}
            </a>
        @endforeach
    </div>
</div>

{{-- Make sure alpine-utils.js is included --}}
<script type="module">
    import { languageSwitcher } from '/build/js/alpine-utils.js';
    window.languageSwitcher = languageSwitcher;
</script>
```

---

## 🐛 Troubleshooting

### Translation Not Showing
1. **Check if key exists:** Look in `lang/{locale}/{domain}.php`
2. **Check override:** Visit `/admin/translations` and search for key
3. **Clear cache:** Click "Clear Cache" in admin or run `php artisan cache:clear`
4. **Check locale:** Verify current locale with `{{ app()->getLocale() }}`

### Language Switch Not Working
1. **Check route:** Should POST to `/locale/{locale}`
2. **Check CSRF token:** Must include CSRF token in form
3. **Check middleware:** Ensure `SetLocale` middleware is active
4. **Check session:** Session must be working to persist locale

### Missing Translations Not Detected
1. **Run statistics:** Visit `/admin/translations/statistics`
2. **Check comparison:** System compares all locales against default
3. **Refresh data:** Clear cache and reload statistics page

### Admin Panel Shows 404
1. **Check route prefix:** Should be `/admin/translations`
2. **Check auth:** Must be authenticated as admin
3. **Check routes:** Run `php artisan route:list | grep translations`

---

## 📊 Performance Tips

### 1. Cache Translations Properly
- Translation files are cached automatically in production
- Database overrides are cached per domain/locale
- API responses cached for 1 hour

### 2. Use Domains Wisely
- Group related translations in same domain
- Smaller domains = more efficient caching
- Common: messages, validation, auth, admin

### 3. Preload Critical Translations
```php
// In AppServiceProvider boot()
use App\Modules\Language\Services\TranslationService;

TranslationService::preload(['messages', 'validation'], app()->getLocale());
```

### 4. Minimize Database Overrides
- Use overrides for customer-specific changes only
- Keep common translations in files
- File-based is faster than database

---

## 🧪 Testing

### Run Integration Test
```bash
bash tests/Manual/test-i18n-module-integration.sh
```

### Manual Testing Checklist
- [ ] Switch language via dropdown - locale persists
- [ ] Create translation override - shows in list
- [ ] Edit override - changes saved
- [ ] Toggle status - active/inactive works
- [ ] Delete override - removed from list
- [ ] View statistics - shows correct numbers
- [ ] View missing - lists untranslated keys
- [ ] Clear cache - confirmation works
- [ ] API endpoint - returns JSON
- [ ] Frontend JS - translations accessible

---

## 🔗 Related Files

- **Main Service:** `app/Modules/Language/Services/TranslationService.php`
- **Locale Service:** `app/Modules/Language/Services/LocaleService.php`
- **Admin Controller:** `app/Modules/Language/Controllers/LanguageOverrideController.php`
- **Locale Controller:** `app/Modules/Language/Controllers/LocaleController.php`
- **Override Model:** `app/Modules/Language/Models/LanguageOverride.php`
- **Admin Views:** `resources/views/admin/translations/`
- **Alpine Component:** `resources/js/alpine-utils.js`
- **API Routes:** `routes/web.php` (translations.export)
- **Admin Routes:** `routes/admin.php`

---

**Last Updated:** 2025-01-XX  
**Module Status:** ✅ Production Ready  
