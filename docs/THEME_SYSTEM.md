# Theme System Documentation

## Architecture Overview

This CMS uses a **Core Views + Theme Assets** architecture for maximum flexibility and maintainability.

## Structure

```
resources/views/
├── admin/                      # CORE ADMIN VIEWS (reusable across themes)
│   ├── layouts/
│   │   └── admin.blade.php    # Master layout with dynamic theme loading
│   ├── articles/
│   ├── categories/
│   ├── pages/
│   └── ...
│
├── themes/                     # PUBLIC THEMES
│   ├── default/
│   └── modern/
│
└── auth/                       # Authentication views

public/themes/                  # THEME ASSETS (CSS/JS/Images)
├── admin/
│   ├── default/
│   │   ├── css/style.css      # Theme-specific styles
│   │   ├── js/script.js       # Theme-specific scripts
│   │   └── images/            # Theme-specific images
│   └── dark/
│       ├── css/style.css      # Different theme, different styles
│       ├── js/script.js
│       └── images/
└── public/
    ├── default/
    └── modern/
```

## How It Works

### 1. Core Views (One View = All Themes)

All admin views are stored in `resources/views/admin/` and are theme-agnostic. They contain:
- HTML structure
- Blade logic
- Content rendering
- Form handling

**Example:** `resources/views/admin/articles/index.blade.php`

```blade
@extends('admin.layouts.admin')

@section('content')
<div class="page-header">
    <h1>Articles</h1>
</div>
<!-- Content here -->
@endsection
```

### 2. Dynamic Theme Loading

The master layout (`admin/layouts/admin.blade.php`) dynamically loads theme-specific assets:

```blade
<html data-theme="{{ active_admin_theme() }}">
<head>
    <!-- Base Tailwind CSS -->
    @vite('resources/css/app.css')
    
    <!-- Theme-specific CSS -->
    <link rel="stylesheet" href="{{ asset('themes/admin/' . active_admin_theme() . '/css/style.css') }}">
</head>
<body class="theme-{{ active_admin_theme() }}">
    <!-- Content -->
    
    <!-- Theme-specific JS -->
    <script src="{{ asset('themes/admin/' . active_admin_theme() . '/js/script.js') }}"></script>
</body>
</html>
```

### 3. Theme Assets Only Contain Styling/Behavior

Each theme folder contains:
- **CSS**: Colors, fonts, spacing variations, animations
- **JS**: Theme-specific interactions, effects
- **Images**: Logos, backgrounds, icons

**DO NOT** duplicate HTML/views in theme folders!

## Creating a New Theme

### Step 1: Create Theme Assets Folder

```bash
mkdir -p public/themes/admin/mytheme/{css,js,images}
```

### Step 2: Create Theme CSS

`public/themes/admin/mytheme/css/style.css`:

```css
:root {
    --theme-primary: #your-color;
    --theme-secondary: #your-color;
}

.theme-mytheme {
    /* Your theme customizations */
}
```

### Step 3: Create Theme JS (Optional)

`public/themes/admin/mytheme/js/script.js`:

```javascript
console.log('My Theme loaded');
// Your theme-specific JavaScript
```

### Step 4: Register Theme in Config

`config/theme.php`:

```php
'admin_themes' => [
    'default' => [
        'name' => 'Default Theme',
        'author' => 'Your Name',
    ],
    'mytheme' => [
        'name' => 'My Custom Theme',
        'author' => 'Your Name',
    ],
],
```

### Step 5: Activate Theme

Update `.env`:

```
ADMIN_THEME_ACTIVE=mytheme
```

Or use ThemeService:

```php
theme()->setActiveAdminTheme('mytheme');
```

## Theme Functions

### Available Helpers

```php
// Get active theme name
active_admin_theme()  // Returns: 'default'

// Get theme asset URL
theme_asset('css/custom.css', 'admin')
// Returns: http://yoursite.com/themes/admin/default/css/custom.css

// Get theme service instance
theme()->setActiveAdminTheme('dark')
theme()->getAdminThemes()
```

## Benefits

✅ **No Code Duplication**: One view file works for all themes
✅ **Easy Maintenance**: Update HTML once, applies to all themes
✅ **Flexible Styling**: Each theme can have completely different look
✅ **Scalable**: Add unlimited themes without duplicating views
✅ **Fast Development**: Focus on styling, not HTML structure

## Migration from Old System

If you have old theme structure with full views:

1. Extract unique HTML structure → Move to `resources/views/admin/`
2. Extract CSS/styling → Move to `public/themes/admin/{theme}/css/`
3. Extract JS behavior → Move to `public/themes/admin/{theme}/js/`
4. Delete duplicate view files
5. Update references to use `view('admin.module.view')`

## Best Practices

1. **Keep views theme-agnostic**: Use CSS classes, not inline styles
2. **Use CSS variables**: Makes theme switching easier
3. **Minimize theme JS**: Keep logic in core, styling in themes
4. **Test with multiple themes**: Ensure compatibility
5. **Document theme requirements**: Note any special dependencies

## Examples

### Example 1: Different Color Schemes

**default/css/style.css**:
```css
:root {
    --theme-primary: #3b82f6;  /* Blue */
}
```

**dark/css/style.css**:
```css
:root {
    --theme-primary: #1f2937;  /* Dark Gray */
}
```

Same HTML, different colors!

### Example 2: Different Animations

**default/js/script.js**:
```javascript
// Fade animations
```

**modern/js/script.js**:
```javascript
// Slide animations
```

Same functionality, different effects!

## Support

For questions or issues:
- Check this documentation
- Review example themes in `public/themes/admin/`
- Examine `ThemeService.php` for available methods
