# PRIORITY 3 - Translation Key Standardization & Audit Plan

## Current Status

- ✅ Admin lang files exist (en/admin.php, id/admin.php)
- ✅ Common translation keys added
- ⏳ Admin views: Partially using translation keys
- ⏳ Frontend views: Mostly hardcoded strings
- ⏳ API responses: Hardcoded messages

## Translation Key Convention

### Pattern
```
admin.{module}.{resource}.{action|field|status}
public.{module}.{resource}.{action|field|status}
common.{key}
messages.{type}.{subject}
```

### Examples
- `admin.articles.index.title` - "Articles Management"
- `admin.articles.create.btn` - "Create Article"
- `admin.articles.form.title_id` - "Title (Indonesian)"
- `admin.articles.status.published` - "Published"
- `public.articles.index.title` - "Our Articles"
- `messages.error.not_found` - "Resource not found"

## Audit Tasks

### PHASE 1: Admin Views Audit (High Priority)
- [ ] Sidebar navigation menu strings
- [ ] Dashboard page labels
- [ ] Articles CRUD pages
- [ ] Categories CRUD pages
- [ ] Tags CRUD pages
- [ ] Pages CRUD pages
- [ ] Users CRUD pages
- [ ] Media management strings
- [ ] Settings page strings
- [ ] Common buttons & actions

### PHASE 2: Frontend Views Audit (High Priority)
- [ ] Homepage strings
- [ ] Article listing page
- [ ] Article detail page
- [ ] Category listing page
- [ ] Search page
- [ ] Common UI strings
- [ ] Form labels & placeholders
- [ ] Error messages

### PHASE 3: API Response Messages (Medium Priority)
- [ ] Error response messages
- [ ] Validation error messages
- [ ] Success messages
- [ ] Status messages

### PHASE 4: Exception Messages (Medium Priority)
- [ ] Exception error messages
- [ ] Validation exception messages
- [ ] Not found messages

## Files to Process

### Admin Language Files
- /var/www/html/cms-app/lang/en/admin.php
- /var/www/html/cms-app/lang/id/admin.php

### Frontend Language Files (To Create)
- /var/www/html/cms-app/lang/en/public.php
- /var/www/html/cms-app/lang/id/public.php

### Admin Views
- resources/views/admin/dashboard.blade.php
- resources/views/admin/articles/**
- resources/views/admin/categories/**
- resources/views/admin/pages/**
- resources/views/admin/users/**
- resources/views/admin/media/**
- resources/views/admin/settings/**

### Frontend Views
- resources/views/public/home.blade.php
- resources/views/public/pages/**

## Strategy

1. **Start with admin views** - Most critical for UX
2. **Add translation keys to lang files** - Extend existing structure
3. **Update blade views** - Replace hardcoded strings with `__()` or `@t()`
4. **Create frontend lang files** - New files for public theme
5. **Update API messages** - Services & exceptions

## Tools & Commands

### Find hardcoded strings
```bash
grep -r "Published\|Draft\|Active\|Inactive" resources/views/admin --include="*.blade.php"
```

### Find missing translations
```bash
grep -r "__\(" resources/views --include="*.blade.php" | grep -v "null\|namespace"
```

## Quality Checks

- [ ] All user-visible strings have translation keys
- [ ] No hardcoded English strings in admin views
- [ ] Translation keys follow convention
- [ ] Lang files are organized by section
- [ ] Both EN and ID translations present
- [ ] No missing translation fallbacks

## Progress Tracking

- [ ] Phase 1 Complete: Admin views audit & update
- [ ] Phase 2 Complete: Frontend views audit & update
- [ ] Phase 3 Complete: API responses
- [ ] Phase 4 Complete: Exception messages
- [ ] Final: Testing & validation

