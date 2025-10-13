# Laravel CMS - Theme System Guide

## 🎨 Theme Architecture

### Core Concept
- **Views** = Structure & Logic (shared across all themes)
- **Assets** = Styling & Behavior (theme-specific, switchable)

---

## 📂 Structure

```
resources/views/admin/              # Core admin views (used by all themes)
├── layouts/admin.blade.php         # Master layout
├── dashboard.blade.php
├── articles/
├── categories/
├── pages/
├── users/
├── media/
├── themes/
└── plugins/

public/themes/admin/                # Theme assets (switchable)
├── default/
│   ├── css/style.css              # Theme-specific styles
│   ├── js/script.js               # Theme-specific scripts
│   └── images/
└── dark/                           # Additional themes...
    ├── css/style.css
    └── js/script.js
```

---

## 🚀 Quick Start

### Access Admin Panel
```
URL: http://your-domain/admin
Login: admin@example.com
Password: admin123
```

### Available Routes
- `/admin` - Dashboard
- `/admin/articles` - Article management
- `/admin/categories` - Category management
- `/admin/pages` - Page management
- `/admin/users` - User management
- `/admin/media` - Media library
- `/admin/themes` - Theme manager
- `/admin/plugins` - Plugin manager

---

## 🎨 Creating New Theme

### Step 1: Create Theme Structure
```bash
mkdir -p public/themes/admin/mytheme/{css,js,images}
```

### Step 2: Create Theme CSS
```css
/* public/themes/admin/mytheme/css/style.css */
:root {
    --theme-primary: #your-color;
    --theme-secondary: #your-color;
}

body.theme-mytheme {
    /* Your custom styles */
}
```

### Step 3: Create Theme JS (Optional)
```javascript
/* public/themes/admin/mytheme/js/script.js */
console.log('My Theme Loaded');
// Your custom JavaScript
```

### Step 4: Register Theme
```php
// config/theme.php
'admin_themes' => [
    'default' => [...],
    'mytheme' => [
        'name' => 'My Theme',
        'description' => 'My custom admin theme',
        'path' => 'themes/admin/mytheme',
    ],
],
```

### Step 5: Activate Theme
```php
// In theme manager or code
theme()->setActiveAdminTheme('mytheme');
```

**Done!** All admin pages now use your theme styling.

---

## 🔧 Theme Helpers

### Get Active Theme
```php
$theme = active_admin_theme();  // Returns: 'default'
```

### Get Theme Asset URL
```php
$css = theme_asset('css/style.css', 'admin');
// Returns: http://your-domain/themes/admin/default/css/style.css
```

### Switch Theme
```php
theme()->setActiveAdminTheme('dark');
```

---

## 📝 Creating Views

All admin views should extend the master layout:

```blade
{{-- resources/views/admin/mymodule/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'My Module')

@section('content')
    <h1>My Module Content</h1>
    <!-- Your content here -->
@endsection

@push('styles')
    <!-- Page-specific CSS -->
    <link rel="stylesheet" href="...">
@endpush

@push('scripts')
    <!-- Page-specific JS -->
    <script>
        // Your JS
    </script>
@endpush
```

---

## 🎯 Key Features

### ✅ Single View, Multiple Themes
- Write view once, works with all themes
- No duplication needed
- Easy maintenance

### ✅ Hot-Swappable Themes
- Switch themes without code changes
- All views automatically adapt
- Instant theme switching

### ✅ Theme Isolation
- Each theme has its own assets
- No conflicts between themes
- Easy to test and develop

---

## 🛠️ Development Workflow

### Clear Caches
```bash
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

### Check Routes
```bash
php artisan route:list | grep admin
```

### Verify Views
```bash
ls -la resources/views/admin/
```

### Test View Exists
```bash
php -r "require 'vendor/autoload.php'; 
\$app = require_once 'bootstrap/app.php'; 
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); 
echo view()->exists('admin.articles.index') ? 'OK' : 'FAIL';"
```

---

## 📚 Best Practices

### 1. Keep Views Generic
- Don't hardcode colors or styles
- Use Tailwind utility classes
- Let themes override via CSS

### 2. Use Theme Variables
```css
:root {
    --theme-primary: #3b82f6;
    --theme-bg: #ffffff;
}

.my-element {
    background: var(--theme-bg);
    color: var(--theme-primary);
}
```

### 3. Leverage Stacks
```blade
@push('styles')
    <!-- Page-specific CSS -->
@endpush

@push('scripts')
    <!-- Page-specific JS -->
@endpush
```

### 4. Test Across Themes
- Test views with multiple themes
- Ensure compatibility
- Check responsive design

---

## 🐛 Troubleshooting

### View Not Found
```bash
# Clear view cache
php artisan view:clear

# Check file exists
ls resources/views/admin/myview.blade.php
```

### Theme Not Loading
```bash
# Check assets exist
ls public/themes/admin/default/css/style.css

# Check theme is active
php artisan tinker --execute="echo active_admin_theme();"
```

### Styles Not Applying
```bash
# Clear browser cache
# Check CSS file path in browser DevTools
# Verify CSS file exists
```

---

## 📖 Resources

- Config: `config/theme.php`
- Service: `app/Modules/Theme/Services/ThemeService.php`
- Helpers: `app/Http/Helpers/theme_helpers.php`
- Master Layout: `resources/views/admin/layouts/admin.blade.php`

---

## 🎓 Learn More

- [Laravel Views Documentation](https://laravel.com/docs/views)
- [Blade Templates](https://laravel.com/docs/blade)
- [Tailwind CSS](https://tailwindcss.com)

---

**Happy Theming! 🎨**
