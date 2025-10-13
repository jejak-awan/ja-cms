# 🚀 Laravel CMS - Quick Start Guide

## Table of Contents
1. [Installation](#installation)
2. [Login Credentials](#login-credentials)
3. [Feature Overview](#feature-overview)
4. [Common Tasks](#common-tasks)
5. [Dark Mode](#dark-mode)
6. [Mobile Access](#mobile-access)
7. [Troubleshooting](#troubleshooting)

---

## Installation

### Requirements
- PHP 8.2+
- Composer
- Node.js 18+
- SQLite (or MySQL/PostgreSQL)

### Setup Steps
```bash
# 1. Install dependencies
composer install
npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Run migrations
php artisan migrate

# 4. Seed default data
php artisan db:seed --class=RolesSeeder
php artisan db:seed --class=MenuItemsSeeder

# 5. Build assets
npm run build

# 6. Start server
php artisan serve
```

Access admin panel at: `http://localhost:8000/admin`

---

## Login Credentials

**Default Admin Account:**
- Email: (check your database or create via tinker)
- Password: (set during registration)

**Create Admin User:**
```bash
php artisan tinker
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role_id' => 1, // Admin role
    'status' => 'active'
]);
```

---

## Feature Overview

### 1. Dashboard (`/admin`)
- Welcome page with stats overview
- Quick links to all modules

### 2. Articles (`/admin/articles`)
- Create/Edit/Delete articles
- TinyMCE WYSIWYG editor
- Categories and tags
- Featured images
- Draft/Published workflow
- SEO metadata

### 3. Categories (`/admin/categories`)
- Hierarchical structure
- Drag-and-drop tree view
- Parent-child relationships
- Toggle active/inactive
- SEO fields

### 4. Pages (`/admin/pages`)
- Create static pages
- Multiple templates:
  - Default
  - Full Width
  - Sidebar Left/Right
  - Landing Page
- Homepage designation
- Drag-and-drop reordering
- SEO optimization

### 5. Media Library (`/admin/media`)
- Modal upload interface
- Drag-and-drop file upload
- Folder organization
- Bulk delete
- Stats: Total files, types, storage
- Supported types: images, documents, videos, audio

### 6. Users (`/admin/users`)
- User management
- Role assignment (Admin, Editor, Author, Subscriber)
- Status control (Active, Inactive, Suspended)
- Search and filters
- Avatar gradients

### 7. Menus (`/admin/menus`)
- WordPress-style menu builder
- 3 item types:
  - Pages (from your pages)
  - Categories (from categories)
  - Custom Links (manual URL)
- Nested menu items (drag right to nest)
- Multiple menu locations (header, footer, sidebar)

### 8. Settings (`/admin/settings`)
- **General Tab**: Site name, contact info
- **SEO Tab**: Meta tags, analytics codes
- **Social Media Tab**: Facebook, Twitter, Instagram, etc.

### 9. Themes (`/admin/themes`)
- (Module exists, implementation pending)

### 10. Plugins (`/admin/plugins`)
- (Module exists, implementation pending)

---

## Common Tasks

### Create a New Article
1. Navigate to **Articles** → **Create Article**
2. Fill in title, content (with TinyMCE)
3. Select category and add tags
4. Upload featured image
5. Set SEO metadata
6. Choose status (Draft/Published)
7. Click **Create Article**

### Build a Menu
1. Go to **Menus** → **Create Menu**
2. Enter menu name (e.g., "main-menu")
3. Click **Edit** on the menu
4. **Left Panel**: Add items
   - **Pages Tab**: Check pages and click "Add Selected Pages"
   - **Categories Tab**: Check categories and click "Add Selected Categories"
   - **Custom Tab**: Enter URL and title, click "Add Custom Link"
5. **Right Panel**: Drag items to reorder
   - Drag right to create sub-menu
   - Drag up/down to reorder
6. Click **Save Order** when done

### Upload Media
1. Go to **Media Library**
2. Click **Upload Media** button
3. Drag files into dropzone OR click to browse
4. (Optional) Enter folder path
5. See file previews
6. Click **Start Upload**
7. Wait for upload to complete

### Manage Users
1. Navigate to **Users**
2. **Create**: Click "Create User", fill form
3. **Edit**: Click "Edit" on user row
4. **Delete**: Click "Delete" (with confirmation)
5. Use filters: Search by name/email, filter by role/status

### Configure Settings
1. Go to **Settings**
2. Switch between tabs: General / SEO / Social Media
3. Fill in relevant fields
4. Click **Save Settings**
5. Settings are cached for performance

---

## Dark Mode

### Toggle Dark Mode
- Click the **sun/moon icon** in the header (top-right)
- Theme preference is saved to localStorage
- Persists across browser sessions

### Manual Toggle
```javascript
// Enable dark mode
document.documentElement.classList.add('dark');
localStorage.setItem('theme', 'dark');

// Disable dark mode
document.documentElement.classList.remove('dark');
localStorage.setItem('theme', 'light');
```

### Customize Dark Mode Colors
Edit `resources/views/admin/layouts/admin.blade.php`:
```css
[data-theme="dark"] {
    --bg-primary: #1a202c;
    --bg-secondary: #2d3748;
    --text-primary: #f7fafc;
    --text-secondary: #cbd5e0;
}
```

---

## Mobile Access

### Mobile Menu
- On mobile (< 768px), sidebar is hidden
- Click **hamburger menu icon** (top-left) to open
- Click **dark overlay** or outside sidebar to close

### Touch Gestures
- All buttons are touch-optimized (minimum 48px tap target)
- Drag-and-drop works on touch devices
- Scrollable content areas with momentum scrolling

### Responsive Breakpoints
- **Mobile**: < 768px (1 column layouts)
- **Tablet**: 768px - 1024px (2-3 columns)
- **Desktop**: > 1024px (3-6 columns)

---

## Troubleshooting

### TinyMCE Not Loading
```bash
# Rebuild assets
npm run build

# Check if tinymce files exist
ls public/build/tinymce/
```

### Dark Mode Not Persisting
```javascript
// Check localStorage in browser console
console.log(localStorage.getItem('theme'));

// Clear and reset
localStorage.removeItem('theme');
location.reload();
```

### Drag-and-Drop Not Working
- Ensure SortableJS is loaded: Check browser console for errors
- Reload the page after adding new items
- Check that `.drag-handle` class exists on items

### Mobile Menu Not Opening
- Check browser console for JavaScript errors
- Verify `sidebar` and `mobile-overlay` IDs exist
- Ensure responsive classes are applied

### Upload Errors
```bash
# Check storage permissions
chmod -R 775 storage/
chmod -R 775 public/storage

# Create symbolic link
php artisan storage:link

# Check upload limits in php.ini
upload_max_filesize = 10M
post_max_size = 10M
```

### Menu Items Not Saving Order
- Check browser console for AJAX errors
- Verify CSRF token is present
- Check route exists: `php artisan route:list --path=menu`
- Ensure JSON response is returned from controller

---

## File Structure Reference

```
cms-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Helpers/
│   │       └── helpers.php
│   └── Modules/
│       ├── Article/
│       │   ├── Controllers/ArticleController.php
│       │   └── Models/Article.php
│       ├── Category/
│       │   ├── Controllers/CategoryController.php
│       │   └── Models/Category.php
│       ├── Page/
│       │   ├── Controllers/PageController.php
│       │   └── Models/Page.php
│       ├── Media/
│       │   ├── Controllers/MediaController.php
│       │   └── Models/Media.php
│       ├── User/
│       │   ├── Controllers/UserController.php
│       │   └── Models/
│       │       ├── User.php
│       │       └── Role.php
│       ├── Setting/
│       │   ├── Controllers/SettingController.php
│       │   └── Models/Setting.php
│       └── Menu/
│           ├── Controllers/MenuController.php
│           └── Models/
│               ├── Menu.php
│               └── MenuItem.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── RolesSeeder.php
│       └── MenuItemsSeeder.php
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── tinymce-config.js
│   └── views/
│       └── admin/
│           ├── layouts/
│           │   └── admin.blade.php
│           ├── articles/
│           ├── categories/
│           ├── pages/
│           ├── media/
│           ├── users/
│           ├── settings/
│           └── menus/
├── routes/
│   ├── admin.php
│   └── web.php
├── public/
│   ├── build/ (generated by Vite)
│   └── storage/ (symlink)
└── storage/
    ├── app/
    │   ├── public/
    │   └── uploads/
    └── logs/
```

---

## Keyboard Shortcuts

### Global
- `Ctrl/Cmd + S`: Save form (if focus is in form)
- `Esc`: Close modals/dropdowns

### TinyMCE Editor
- `Ctrl/Cmd + B`: Bold
- `Ctrl/Cmd + I`: Italic
- `Ctrl/Cmd + U`: Underline
- `Ctrl/Cmd + K`: Insert link
- `Ctrl/Cmd + Z`: Undo
- `Ctrl/Cmd + Y`: Redo

---

## API Endpoints (AJAX)

### Media Upload
```javascript
POST /admin/media
Headers: X-CSRF-TOKEN, Content-Type: multipart/form-data
Body: FormData with files and folder path
Response: { success: true, message: '...' }
```

### Menu Order Update
```javascript
PUT /admin/menus/{menu}/order
Headers: X-CSRF-TOKEN, Content-Type: application/json
Body: { items: [{ id, parent_id, order }] }
Response: { success: true }
```

### Menu Item Add
```javascript
POST /admin/menus/{menu}/items
Headers: X-CSRF-TOKEN, Content-Type: application/json
Body: { type: 'page|category|custom', title, url, ... }
Response: { success: true, item: {...} }
```

### Category Reorder
```javascript
POST /admin/categories/reorder
Headers: X-CSRF-TOKEN, Content-Type: application/json
Body: { order: { id: order_number } }
Response: { success: true }
```

---

## Performance Tips

1. **Enable Caching**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Optimize Autoloader**:
   ```bash
   composer dump-autoload --optimize
   ```

3. **Production Build**:
   ```bash
   npm run build
   ```

4. **Database Indexing**:
   - Already optimized with indexes on foreign keys, slugs, status

5. **Image Optimization**:
   - Use responsive images
   - Compress uploads with intervention/image package

---

## Security Checklist

- [x] CSRF protection on all forms
- [x] Authentication on admin routes
- [x] Password hashing with bcrypt
- [x] SQL injection prevention (Eloquent)
- [x] XSS protection (Blade escaping)
- [x] Input validation on all forms
- [x] Self-deletion protection for users
- [ ] Rate limiting (optional, add to routes)
- [ ] Two-factor authentication (optional)
- [ ] IP whitelisting (optional)

---

## Getting Help

### Documentation
- Laravel Docs: https://laravel.com/docs
- Tailwind CSS: https://tailwindcss.com/docs
- TinyMCE: https://www.tiny.cloud/docs/
- SortableJS: https://github.com/SortableJS/Sortable

### Common Issues
1. **Permission Denied**: `chmod -R 775 storage bootstrap/cache`
2. **Class Not Found**: `composer dump-autoload`
3. **Route Not Found**: `php artisan route:clear`
4. **View Not Found**: `php artisan view:clear`
5. **Assets 404**: `npm run build && php artisan storage:link`

---

## Credits

**Built with:**
- Laravel 11
- Tailwind CSS 4.0
- TinyMCE 5.10.x
- SortableJS 1.15.0
- Vite 7.1.9

**Created**: October 2025
**Version**: 1.0.0
**Status**: Production Ready ✅

---

**Happy Coding! 🚀**
