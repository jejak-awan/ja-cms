#!/bin/bash

# Phase 4 Testing Script
# Tests frontend i18n implementation

echo "======================================"
echo "🧪 Phase 4 Frontend i18n Testing"
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

echo "📦 Testing Middleware & Components..."
echo "------------------------------"

# Check LocalizeRoutes middleware
[ -f "app/Http/Middleware/LocalizeRoutes.php" ]
test_check "LocalizeRoutes middleware exists"

# Check LocalizedRouteMacros helper
[ -f "app/Support/LocalizedRouteMacros.php" ]
test_check "LocalizedRouteMacros helper exists"

echo ""
echo "🎨 Testing Layout Updates..."
echo "------------------------------"

# Check if public layout has i18n support
grep -q "@locale" resources/views/public/layouts/app.blade.php
test_check "Public layout uses @locale directive"

# Check if Alpine.js is imported via Vite (NPM version)
grep -q "@vite('resources/js/app.js')" resources/views/public/layouts/app.blade.php
test_check "Alpine.js NPM version loaded via Vite"

# Check if language switcher is integrated
grep -q "x-language-switcher" resources/views/public/layouts/app.blade.php
test_check "Language switcher integrated in public layout"

# Check auth layout
grep -q "@locale" resources/views/layouts/auth.blade.php
test_check "Auth layout uses @locale directive"

echo ""
echo "🌐 Testing Translations..."
echo "------------------------------"

# Check navigation translations
grep -q "@t('messages.home')" resources/views/public/layouts/app.blade.php
test_check "Navigation uses translation directives"

# Check footer translations
grep -q "@t('messages.quick_links')" resources/views/public/layouts/app.blade.php
test_check "Footer uses translation directives"

# Check if new translation keys exist
grep -q "about" lang/id/messages.php
test_check "Frontend translations in id/messages.php"

grep -q "about" lang/en/messages.php
test_check "Frontend translations in en/messages.php"

# Check auth translations
grep -q "authentication" lang/id/auth.php
test_check "Auth translations in id/auth.php"

grep -q "authentication" lang/en/auth.php
test_check "Auth translations in en/auth.php"

echo ""
echo "🛣️  Testing Localized Routes..."
echo "------------------------------"

# Check if localized routes are registered
php artisan route:list 2>&1 | grep -q "en\.home"
test_check "English home route registered (en.home)"

php artisan route:list 2>&1 | grep -q "id\.home"
test_check "Indonesian home route registered (id.home)"

php artisan route:list 2>&1 | grep -q "en\.articles"
test_check "English articles routes registered"

php artisan route:list 2>&1 | grep -q "id\.articles"
test_check "Indonesian articles routes registered"

# Check middleware registration
grep -q "localize.routes" bootstrap/app.php
test_check "LocalizeRoutes middleware registered"

echo ""
echo "🔧 Testing Configuration..."
echo "------------------------------"

# Check if route macros are registered
grep -q "LocalizedRouteMacros::register" app/Providers/AppServiceProvider.php
test_check "Route macros registered in AppServiceProvider"

# Check middleware alias
grep -q "localize.routes" bootstrap/app.php
test_check "Middleware alias registered"

echo ""
echo "📄 Testing Views..."
echo "------------------------------"

# Check home page translations
grep -q "@t('messages.welcome" resources/views/public/home.blade.php
test_check "Home page uses translation directives"

# Check multilingual directive usage
grep -q "@multilingual" resources/views/public/layouts/app.blade.php
test_check "Layout uses @multilingual directive"

echo ""
echo "======================================"
echo "📊 Test Summary"
echo "======================================"
echo -e "${GREEN}Passed: $PASSED${NC}"
echo -e "${RED}Failed: $FAILED${NC}"
echo "Total: $((PASSED + FAILED))"
echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}🎉 All tests passed! Phase 4 is complete!${NC}"
    exit 0
else
    echo -e "${YELLOW}⚠️  Some tests failed. Please review the results above.${NC}"
    exit 1
fi