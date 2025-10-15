# 🚀 Cache System - Quick Guide

## 📌 Overview

Sistem cache telah diperbaiki dan siap digunakan. Dokumentasi lengkap ada di `docs/CACHE_SYSTEM_REVIEW.md`.

---

## ✅ Yang Sudah Diperbaiki

1. ✅ **CacheHelper class dibuat** - File yang hilang sekarang sudah ada
2. ✅ **Metrics tracking diperbaiki** - Hit rate sekarang akurat
3. ✅ **Error handling ditambahkan** - Graceful fallback
4. ✅ **Tests dibuat** - Comprehensive test coverage

---

## 🎯 Quick Start

### Basic Usage - CacheHelper

```php
use App\Support\CacheHelper;

// Cache data selama 15 menit (900 detik)
$articles = CacheHelper::remember('featured_articles', 'article', 900, function() {
    return Article::where('featured', true)->get();
});

// Store data
CacheHelper::put('my_key', 'my_value', 'module_name', 3600);

// Get data
$value = CacheHelper::get('my_key', 'default_value');

// Check if exists
if (CacheHelper::has('my_key')) {
    // Key exists
}

// Delete specific key
CacheHelper::forget('my_key');

// Delete by module (Redis/Memcached only)
CacheHelper::flushByModule('article');

// Clear all cache
CacheHelper::flush();
```

---

## 🔧 Testing

### Run Tests
```bash
# Run all cache tests
php artisan test --filter CacheSystemTest

# Run CacheHelper tests only
php artisan test --filter CacheHelperTest

# Run test runner script
./tests/cache-test-runner.sh
```

---

## 📊 Cache Operations

### Via Controller (API)
```bash
# Get cache status
curl http://localhost/api/cache/status

# Get cache metrics
curl http://localhost/api/cache/metrics

# Clear all cache
curl -X POST http://localhost/api/cache/clear

# Warm up cache
curl -X POST http://localhost/api/cache/warm-up
```

### Via Artisan
```bash
# Clear cache
php artisan cache:clear

# Show config
php artisan config:show cache
```

---

## 🎨 Examples

### 1. Cache Homepage Data
```php
// In PublicController
public function index()
{
    $articles = CacheHelper::remember('homepage_articles', 'article', 900, function() {
        return Article::published()->featured()->take(5)->get();
    });
    
    return view('home', compact('articles'));
}
```

### 2. Cache with Auto-Invalidation
```php
// In ArticleObserver
public function saved(Article $article)
{
    // Clear all article cache when article is saved
    CacheHelper::flushByModule('article');
}
```

### 3. Cache Multiple Values
```php
$data = CacheHelper::putMany([
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
], 'module', 3600);

$values = CacheHelper::many(['key1', 'key2', 'key3']);
```

---

## ⚙️ Configuration

### Environment Variables (.env)
```env
CACHE_STORE=file          # file, database, redis, memcached
CACHE_PREFIX=myapp_cache  # Optional prefix
```

### Recommended Settings

**Development:**
```env
CACHE_STORE=file
```

**Production:**
```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

---

## 🚨 Common Issues & Solutions

### Issue 1: "Class CacheHelper not found"
**Solution**: File sudah dibuat di `app/Support/CacheHelper.php`. Run:
```bash
composer dump-autoload
```

### Issue 2: Cache not working
**Solution**: Check permissions
```bash
sudo chmod -R 775 storage/framework/cache
sudo chown -R www-data:www-data storage/framework/cache
```

### Issue 3: Metrics not accurate
**Solution**: Fixed! Metrics sekarang track dengan benar.

### Issue 4: Cache permission denied
**Solution**: 
```bash
sudo chmod -R 775 storage/framework/cache
sudo chown -R $USER:www-data storage/framework/cache
```

---

## 📈 Performance Tips

### 1. Use Appropriate TTL
```php
// Short-lived data (5 minutes)
CacheHelper::remember('recent_posts', 'article', 300, $callback);

// Medium-lived data (1 hour)
CacheHelper::remember('categories', 'category', 3600, $callback);

// Long-lived data (1 day)
CacheHelper::remember('settings', 'setting', 86400, $callback);
```

### 2. Cache Warming
```php
// In Kernel.php schedule
$schedule->call(function () {
    app(CacheService::class)->warmUp();
})->hourly();
```

### 3. Use Tags for Better Invalidation
```php
// Cache with module tags
CacheHelper::remember('article_123', 'article', 3600, $callback);

// Clear all article cache at once
CacheHelper::flushByModule('article');
```

---

## 📚 API Reference

### CacheHelper Methods

| Method | Description | Parameters |
|--------|-------------|------------|
| `remember()` | Cache with callback | `$key, $module, $ttl, $callback` |
| `put()` | Store value | `$key, $value, $module, $ttl` |
| `get()` | Get value | `$key, $default` |
| `has()` | Check existence | `$key` |
| `forget()` | Delete key | `$key` |
| `flushByModule()` | Clear by module | `$module` |
| `flush()` | Clear all | - |
| `many()` | Get multiple | `$keys, $default` |
| `putMany()` | Store multiple | `$values, $module, $ttl` |

### CacheService Methods

| Method | Description | Return |
|--------|-------------|--------|
| `getStats()` | Get statistics | `array` |
| `getDriverStats()` | Get driver info | `array` |
| `warmUp()` | Warm cache | `array` |
| `flush()` | Clear all | `bool` |

---

## 🎯 Best Practices

### ✅ DO:
- Use module-based tagging (`'article'`, `'category'`, etc.)
- Set appropriate TTL based on data volatility
- Clear cache when data changes (via Observers)
- Monitor cache hit rate
- Use Redis in production

### ❌ DON'T:
- Cache user-specific data without user ID in key
- Set extremely long TTL (> 1 day) for dynamic data
- Forget to handle cache failures
- Cache everything (cache what's expensive to compute)

---

## 📞 Support

- Full docs: `docs/CACHE_SYSTEM_REVIEW.md`
- Tests: `tests/Feature/CacheSystemTest.php`
- Test runner: `./tests/cache-test-runner.sh`

**Status**: ✅ **READY FOR PRODUCTION**

