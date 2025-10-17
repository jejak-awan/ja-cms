# Dependencies Documentation

Last Updated: October 17, 2025

## System Requirements

### Runtime Environment
- **PHP**: 8.2+ or 8.3+ (Current: 8.3.6)
- **Node.js**: 22.x LTS (Current: 22.20.0)
- **npm**: 10.x (Current: 10.9.3)

### Recommended Setup
- **Web Server**: Nginx 1.24+ or Apache 2.4+
- **Database**: MySQL 8.0+ / PostgreSQL 15+ / SQLite 3.40+
- **Redis**: 7.0+ (for caching and queues)
- **Memory**: PHP memory_limit >= 256M
- **Max Upload**: 100M (adjustable)

---

## PHP Dependencies (Composer)

### Production Dependencies

| Package | Version | Purpose | Notes |
|---------|---------|---------|-------|
| `php` | ^8.2\|^8.3 | Runtime | PHP 8.3 recommended for best performance |
| `laravel/framework` | ^12.34 | Core Framework | Laravel 12 LTS with latest security patches |
| `laravel/sanctum` | ^4.2 | API Authentication | Token-based auth for SPAs/APIs |
| `laravel/fortify` | ^1.31 | Authentication Backend | 2FA, password reset, email verification |
| `laravel/tinker` | ^2.10 | REPL Console | Interactive PHP console |
| `intervention/image` | ^3.11 | Image Processing | Modern PHP image manipulation |
| `pragmarx/google2fa-laravel` | ^2.3 | Two-Factor Auth | Google Authenticator integration |
| `predis/predis` | ^3.2 | Redis Client | PHP Redis library for caching/queues |

### Development Dependencies

| Package | Version | Purpose | Notes |
|---------|---------|---------|-------|
| `fakerphp/faker` | ^1.24 | Test Data Generation | Fake data for seeding |
| `laravel/pail` | ^1.2 | Log Viewer | Real-time log monitoring |
| `laravel/pint` | ^1.25 | Code Formatter | Opinionated PHP code style |
| `laravel/sail` | ^1.46 | Docker Dev Environment | Optional Docker setup |
| `mockery/mockery` | ^1.6 | Mocking Framework | Test doubles for PHP |
| `nunomaduro/collision` | ^8.8 | Error Handler | Beautiful error reporting |
| `pestphp/pest` | ^3.8 | Testing Framework | Modern PHP testing |
| `pestphp/pest-plugin-laravel` | ^3.2 | Laravel Integration | Pest + Laravel helpers |
| `phpunit/phpunit` | ^11.5 | Testing Framework | Unit testing foundation |

---

## JavaScript Dependencies (npm)

### Development Dependencies

| Package | Version | Purpose | Notes |
|---------|---------|---------|-------|
| `vite` | ^7.1.10 | Build Tool | Fast ES modules bundler |
| `laravel-vite-plugin` | ^2.0.1 | Laravel Integration | Vite + Laravel bridge |
| `tailwindcss` | ^4.1.14 | CSS Framework | Utility-first CSS (v4) |
| `@tailwindcss/vite` | ^4.1.14 | Tailwind Plugin | Vite integration for Tailwind |
| `alpinejs` | ^3.15.0 | JS Framework | Lightweight reactive framework |
| `axios` | ^1.12.2 | HTTP Client | Promise-based HTTP requests |
| `autoprefixer` | ^10.4.21 | CSS PostProcessor | Vendor prefixes automation |
| `postcss` | ^8.5.6 | CSS Processor | CSS transformations |
| `concurrently` | ^9.2.1 | Process Manager | Run multiple commands |
| `vite-plugin-static-copy` | ^3.1.4 | Asset Copier | Copy static assets in Vite |

### Production Dependencies

| Package | Version | Purpose | Notes |
|---------|---------|---------|-------|
| `chart.js` | ^4.5.1 | Data Visualization | Interactive charts/graphs |
| `tinymce` | 6.8.6 | Rich Text Editor | Last self-hosted version (no API key required) |

---

## Version Compatibility Matrix

| Component | Version | Compatible With |
|-----------|---------|-----------------|
| Laravel | 12.34 | PHP 8.2+, MySQL 8+, Redis 7+ |
| Tailwind CSS | 4.1 | Vite 7+, PostCSS 8+ |
| Alpine.js | 3.15 | All modern browsers |
| Vite | 7.1 | Node 22+, Laravel 12+ |
| TinyMCE | 6.8.6 | ES6+ browsers, Self-hosted (no API key) |
| Chart.js | 4.5 | ES6+ browsers |

---

## Browser Support

### Minimum Requirements
- **Chrome/Edge**: Version 90+
- **Firefox**: Version 88+
- **Safari**: Version 14+
- **Opera**: Version 76+

### Recommended
- Latest versions of all major browsers
- JavaScript enabled
- Cookies enabled for authentication

---

## Security Considerations

### Dependency Management
- All packages use semantic versioning (`^` for minor/patch updates)
- Regular security audits via `composer audit` and `npm audit`
- No known vulnerabilities in current dependency tree

### Authentication
- Two-Factor Authentication (2FA) with Google Authenticator
- Session-based auth with CSRF protection
- API token authentication via Sanctum
- Secure password hashing (bcrypt/argon2)

### Asset Security
- Content Security Policy (CSP) headers
- Subresource Integrity (SRI) for CDN assets
- HTTPS-only in production
- XSS protection via Blade escaping

### TinyMCE Security Configuration
**Version**: 6.8.6 (Last self-hosted version)

**Known Vulnerability**: CVE GHSA-5359-pvf2-pw78
- **Issue**: XSS vulnerability in handling external SVG files through Object/Embed elements
- **Severity**: Moderate
- **Fixed in**: TinyMCE 7.0.0+ (requires API key)

**Mitigation Applied**:
```javascript
// Disabled dangerous elements
invalid_elements: 'object,embed,applet,script'

// Restricted valid elements
extended_valid_elements: 'img[class|src|...]'

// Content sanitization
protect: [
    /<script[\s\S]*?<\/script>/gi,
    /style="[^"]*"/gi,
    /on\w+="[^"]*"/gi
]

// Prevent external resource loading
convert_urls: false
relative_urls: true
remove_script_host: true
```

**Additional Security Measures**:
1. Server-side content sanitization (Laravel HTMLPurifier recommended)
2. Content Security Policy (CSP) headers to prevent inline scripts
3. Input validation on all user-generated content
4. Regular security audits

**Why v6.8.6 instead of v7+?**
- TinyMCE v7+ requires Tiny Cloud API key (subscription-based)
- v6.8.6 is the last fully self-hosted version
- All core features available without external dependencies
- XSS vulnerability mitigated through configuration
- No API key = No vendor lock-in, no external calls, full control

---

## Development Workflow

### Initial Setup
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Build assets
npm run build
```

### Development Commands
```bash
# Start dev server with hot reload
composer dev

# Or run separately:
php artisan serve                 # Laravel server
npm run dev                       # Vite dev server
php artisan queue:listen          # Queue worker
php artisan pail                  # Log viewer
```

### Update Dependencies
```bash
# Update Composer packages
composer update --with-all-dependencies

# Update npm packages
npm update

# Rebuild assets
npm run build

# Clear cache
php artisan optimize:clear
```

### Code Quality
```bash
# Format code
./vendor/bin/pint

# Run tests
php artisan test

# Check for security issues
composer audit
npm audit
```

---

## Production Deployment

### Pre-Deployment Checklist
- [ ] Update all dependencies to latest stable versions
- [ ] Run `composer audit` and `npm audit`
- [ ] Run test suite: `php artisan test`
- [ ] Format code: `./vendor/bin/pint`
- [ ] Build assets: `npm run build`
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper cache/session/queue drivers
- [ ] Enable OPcache in `php.ini`
- [ ] Setup SSL/TLS certificates
- [ ] Configure firewall rules

### Deployment Commands
```bash
# Pull latest code
git pull origin main

# Install/update dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Restart services
php artisan queue:restart
php artisan cache:clear
```

### Performance Optimization
- Enable OPcache and JIT in PHP 8.3
- Use Redis for cache/sessions/queues
- Enable HTTP/2 in web server
- Configure CDN for static assets
- Enable gzip/brotli compression
- Use database connection pooling
- Queue long-running tasks

---

## Troubleshooting

### Common Issues

**1. npm install fails**
```bash
# Clear cache and retry
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
```

**2. Composer timeout**
```bash
# Increase timeout
export COMPOSER_PROCESS_TIMEOUT=600
composer install
```

**3. Asset build fails**
```bash
# Clear Vite cache
rm -rf node_modules/.vite
npm run build
```

**4. Class not found after update**
```bash
# Regenerate autoload
composer dump-autoload
php artisan optimize:clear
```

**5. TinyMCE not loading**
- Check if `tinymce` is in `public/build/assets/`
- Verify `vite-plugin-static-copy` configuration
- Clear browser cache

---

## Maintenance Schedule

### Daily
- Monitor logs via `php artisan pail`
- Check queue status
- Monitor disk space

### Weekly
- Review failed jobs
- Check application logs
- Monitor performance metrics

### Monthly
- Update dependencies (patch versions)
- Review security advisories
- Database optimization
- Clear old logs

### Quarterly
- Major dependency updates (minor versions)
- Performance audit
- Security audit
- Code review

---

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs/12.x)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Alpine.js Documentation](https://alpinejs.dev)
- [Vite Documentation](https://vite.dev)
- [TinyMCE Documentation](https://www.tiny.cloud/docs/)
- [Chart.js Documentation](https://www.chartjs.org/docs/)

---

## Changelog

### 2025-10-17 (Updated)
- ✅ Updated Laravel Framework: 12.33 → 12.34
- ✅ Updated TinyMCE: 5.10.9 → 6.8.6 (Last self-hosted version)
- ✅ Updated Tailwind CSS: 4.0.0 → 4.1.14
- ✅ Updated Vite: 7.0.7 → 7.1.10
- ✅ Updated Alpine.js: 3.14.3 → 3.15.0
- ✅ Updated Axios: 1.11.0 → 1.12.2
- ✅ Updated Concurrently: 9.0.1 → 9.2.1
- ✅ Updated multiple dev dependencies
- ✅ XSS vulnerability mitigation applied for TinyMCE
- ✅ Compatibility verified with PHP 8.3.6 and Node 22.20.0

### Notes
- **TinyMCE v6.8.6**: Last version that can be self-hosted without API key
  - TinyMCE v7+ requires Tiny Cloud API key for most features
  - v6.8.6 is fully functional with all core features locally
  - XSS vulnerability (GHSA-5359-pvf2-pw78) mitigated via configuration:
    * Disabled external SVG/object/embed elements
    * Added content sanitization rules
    * Restricted valid elements to prevent script injection
- Tailwind CSS v4 uses new architecture with Vite plugin
- All packages using stable LTS versions
- High compatibility matrix maintained

