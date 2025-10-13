# 🧪 Manual Test Scripts

This folder contains **manual verification scripts** for testing Laravel CMS modules and features. These scripts are designed for quick validation and demonstration purposes.

---

## 📋 Available Tests

### 1. **test_final_complete.php** ⭐
**Complete System Verification**

The comprehensive test covering all 10 TODOs and system components.

**What it tests:**
- All module statistics (Page, Media, User, Settings, Menu)
- RBAC system (roles, permissions)
- Search functionality
- Breadcrumb service
- Menu system
- Database records count

**Run:**
```bash
php tests/Manual/test_final_complete.php
```

**Output:** Beautiful formatted report with statistics, features, and completion status.

---

### 2. **test_page_module.php**
**Page Module Testing**

Tests hierarchical page functionality.

**What it tests:**
- Page creation with parent-child relationships
- Auto-generation (slug, excerpt, SEO meta)
- Circular reference prevention
- Duplicate slug handling (auto-increment)
- Tree structure operations
- Breadcrumb generation
- Full path generation

**Run:**
```bash
php tests/Manual/test_page_module.php
```

**Creates:** 7 test pages including nested structure

---

### 3. **test_media_module.php**
**Media Upload & Processing**

Tests media library with image processing.

**What it tests:**
- Media upload functionality
- Thumbnail generation (300px with aspect ratio)
- Metadata extraction (width, height, aspect_ratio)
- File type detection
- Folder management
- Image/document scopes
- Storage statistics

**Run:**
```bash
php tests/Manual/test_media_module.php
```

**Requires:** Sample image file (creates test.jpg if not exists)

---

### 4. **test_user_module.php**
**User & RBAC System**

Tests user management and role-based access control.

**What it tests:**
- User creation
- Role assignment (Administrator, Editor, Author)
- Permission checking (direct and via roles)
- User scopes (active, admins, editors, authors)
- Status management (active/inactive/suspended)
- Email verification
- Last login tracking
- Gravatar integration

**Run:**
```bash
php tests/Manual/test_user_module.php
```

**Creates:** 3 test users with different roles

---

## 🚀 How to Use

### Run Individual Test
```bash
# From project root
php tests/Manual/test_page_module.php
php tests/Manual/test_media_module.php
php tests/Manual/test_user_module.php
```

### Run Complete System Test
```bash
php tests/Manual/test_final_complete.php
```

### Run All Tests
```bash
# Using loop
for file in tests/Manual/test_*.php; do
    echo "Running $file..."
    php "$file"
    echo "---"
done
```

---

## 📊 Test Coverage

| Module | Test File | Status |
|--------|-----------|--------|
| Complete System | test_final_complete.php | ✅ Available |
| Articles | *(Built-in to complete test)* | ✅ Covered |
| Categories | *(Built-in to complete test)* | ✅ Covered |
| Pages | test_page_module.php | ✅ Available |
| Media | test_media_module.php | ✅ Available |
| Users & RBAC | test_user_module.php | ✅ Available |
| Settings | *(Built-in to complete test)* | ✅ Covered |
| Menus | *(Built-in to complete test)* | ✅ Covered |
| Search | *(Built-in to complete test)* | ✅ Covered |

---

## ⚠️ Important Notes

### Database State
- These tests **create real data** in the database
- They do **NOT** rollback automatically
- Run in development environment only
- Consider backing up database before running

### Cleanup
If you want to clean test data:
```bash
# Reset database
php artisan migrate:fresh --seed

# Or manually delete test records
php artisan tinker
# Then: User::where('email', 'like', '%@example.com')->delete()
```

### Dependencies
All tests require:
- ✅ Laravel application bootstrapped
- ✅ Database configured and migrated
- ✅ Seeders run (for roles, permissions, settings)
- ✅ Intervention Image installed (for media tests)

---

## 🎯 When to Use These Tests

### ✅ Use When:
- Verifying system functionality after deployment
- Demonstrating features to stakeholders
- Quick validation after code changes
- Documentation purposes
- Learning how modules work

### ❌ Don't Use When:
- Running automated CI/CD pipelines (use PHPUnit instead)
- Production environments
- Need transaction rollback
- Want isolated unit tests

---

## 🔄 Alternative: PHPUnit Tests

For automated testing with rollback and isolation, use PHPUnit:

```bash
# Run PHPUnit tests
php artisan test

# Run specific test
php artisan test --filter=ArticleTest
```

PHPUnit tests are located in:
- `tests/Unit/` - Isolated unit tests
- `tests/Feature/` - Integration tests

---

## 📖 Related Documentation

- **[Complete Project Report](../../docs/PROJECT_COMPLETION_REPORT.md)** - Full documentation
- **[Verification Report](../../docs/VERIFICATION_REPORT.txt)** - Test results
- **[README.md](../../README.md)** - Project overview

---

## 🛠️ Troubleshooting

### Test Fails to Run
```bash
# Check PHP version
php -v  # Should be PHP 8.2+

# Check Laravel installation
php artisan --version

# Check database connection
php artisan tinker
# Then: DB::connection()->getPdo()
```

### Missing Dependencies
```bash
# Install Intervention Image (required for media tests)
composer require intervention/image

# Clear cache
php artisan cache:clear
php artisan config:clear
```

### Permission Issues
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## ✅ Test Validation

After running tests, verify:

1. **Database Records**
   ```bash
   php artisan tinker
   # Check: User::count(), Page::count(), Media::count()
   ```

2. **File System**
   ```bash
   ls -la storage/app/public/uploads/
   ls -la storage/app/public/uploads/thumbs/
   ```

3. **System State**
   ```bash
   php tests/Manual/test_final_complete.php
   ```

---

**Last Updated:** January 12, 2025  
**Status:** All tests passing ✅  
**Purpose:** Manual verification and demonstration
