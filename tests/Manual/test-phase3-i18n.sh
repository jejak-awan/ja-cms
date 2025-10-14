#!/bin/bash

# Phase 3 Testing Script
# Tests all i18n components for Admin Panel

echo "======================================"
echo "🧪 Phase 3 i18n Testing Script"
echo "======================================"
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test counter
PASSED=0
FAILED=0

# Helper function
test_check() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ PASSED${NC}: $1"
        ((PASSED++))
    else
        echo -e "${RED}✗ FAILED${NC}: $1"
        ((FAILED++))
    fi
}

echo "📦 Testing File Existence..."
echo "------------------------------"

# Check if LocaleController exists
[ -f "app/Http/Controllers/LocaleController.php" ]
test_check "LocaleController.php exists"

# Check if BladeServiceProvider exists
[ -f "app/Providers/BladeServiceProvider.php" ]
test_check "BladeServiceProvider.php exists"

# Check if language-switcher component exists
[ -f "resources/views/components/language-switcher.blade.php" ]
test_check "language-switcher.blade.php component exists"

# Check if i18n demo exists
[ -f "resources/views/i18n-demo.blade.php" ]
test_check "i18n-demo.blade.php exists"

# Check if SetLocale middleware exists
[ -f "app/Http/Middleware/SetLocale.php" ]
test_check "SetLocale.php middleware exists"

echo ""
echo "🔧 Testing Configuration..."
echo "------------------------------"

# Check if BladeServiceProvider is registered
grep -q "BladeServiceProvider" bootstrap/providers.php
test_check "BladeServiceProvider registered in bootstrap/providers.php"

# Check if locales config exists
[ -f "config/locales.php" ]
test_check "locales.php config file exists"

echo ""
echo "🛣️  Testing Routes..."
echo "------------------------------"

# Test locale switch route
php artisan route:list 2>&1 | grep -q "locale.switch"
test_check "locale.switch route registered"

# Test locale current API route
php artisan route:list 2>&1 | grep -q "locale.current"
test_check "locale.current API route registered"

# Test i18n demo route
php artisan route:list 2>&1 | grep -q "i18n.demo"
test_check "i18n.demo route registered"

echo ""
echo "🌐 Testing Translation Files..."
echo "------------------------------"

# Check Indonesian admin translations
[ -f "lang/id/admin.php" ]
test_check "Indonesian admin.php exists"

# Check English admin translations
[ -f "lang/en/admin.php" ]
test_check "English admin.php exists"

# Check if language switcher translations exist in admin.php
grep -q "switch_language" lang/id/admin.php
test_check "Language switcher translations in id/admin.php"

grep -q "switch_language" lang/en/admin.php
test_check "Language switcher translations in en/admin.php"

# Check Indonesian messages
[ -f "lang/id/messages.php" ]
test_check "Indonesian messages.php exists"

# Check English messages
[ -f "lang/en/messages.php" ]
test_check "English messages.php exists"

echo ""
echo "📚 Testing Helper Functions..."
echo "------------------------------"

# Check if helpers file exists
[ -f "app/Support/helpers.php" ]
test_check "helpers.php file exists"

# Check for specific helper functions
grep -q "function current_locale" app/Support/helpers.php
test_check "current_locale() helper exists"

grep -q "function locale_name" app/Support/helpers.php
test_check "locale_name() helper exists"

grep -q "function locale_flag" app/Support/helpers.php
test_check "locale_flag() helper exists"

grep -q "function is_locale" app/Support/helpers.php
test_check "is_locale() helper exists"

grep -q "function active_languages" app/Support/helpers.php
test_check "active_languages() helper exists"

grep -q "function format_date_locale" app/Support/helpers.php
test_check "format_date_locale() helper exists"

echo ""
echo "🎨 Testing UI Components..."
echo "------------------------------"

# Check if Alpine.js is added to admin layout
grep -q "alpinejs" resources/views/admin/layouts/admin.blade.php
test_check "Alpine.js CDN added to admin layout"

# Check if language switcher is integrated in admin layout
grep -q "x-language-switcher" resources/views/admin/layouts/admin.blade.php
test_check "Language switcher integrated in admin layout"

echo ""
echo "📄 Testing Documentation..."
echo "------------------------------"

# Check if Phase 3 documentation exists
[ -f "docs/I18N_PHASE_3_COMPLETE.md" ]
test_check "Phase 3 documentation exists"

echo ""
echo "======================================"
echo "📊 Test Summary"
echo "======================================"
echo -e "${GREEN}Passed: $PASSED${NC}"
echo -e "${RED}Failed: $FAILED${NC}"
echo "Total: $((PASSED + FAILED))"
echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}🎉 All tests passed! Phase 3 is complete!${NC}"
    exit 0
else
    echo -e "${YELLOW}⚠️  Some tests failed. Please review the results above.${NC}"
    exit 1
fi
