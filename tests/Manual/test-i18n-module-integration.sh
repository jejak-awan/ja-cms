#!/bin/bash
# I18n Module Integration Test
# Testing all components after restructuring to module

echo "========================================"
echo "🧪 I18N MODULE INTEGRATION TEST"
echo "========================================"
echo ""

PASS=0
FAIL=0

# Color codes
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

test_pass() {
    echo -e "${GREEN}✓${NC} $1"
    ((PASS++))
}

test_fail() {
    echo -e "${RED}✗${NC} $1"
    ((FAIL++))
}

test_info() {
    echo -e "${YELLOW}ℹ${NC} $1"
}

echo "1️⃣  Module Structure Tests"
echo "----------------------------"

# Test file existence in module
if [ -f "app/Modules/Language/Services/TranslationService.php" ]; then
    test_pass "TranslationService in module"
else
    test_fail "TranslationService missing"
fi
[ -f "app/Modules/Language/Services/LocaleService.php" ] && test_pass "LocaleService in module" || test_fail "LocaleService missing"
[ -f "app/Modules/Language/Controllers/LocaleController.php" ] && test_pass "LocaleController in module" || test_fail "LocaleController missing"
[ -f "app/Modules/Language/Controllers/LanguageOverrideController.php" ] && test_pass "LanguageOverrideController in module" || test_fail "LanguageOverrideController missing"
[ -f "app/Modules/Language/Models/Language.php" ] && test_pass "Language model in module" || test_fail "Language model missing"
[ -f "app/Modules/Language/Models/LanguageOverride.php" ] && test_pass "LanguageOverride model in module" || test_fail "LanguageOverride model missing"
[ -f "app/Modules/Language/Middleware/DetectLanguage.php" ] && test_pass "DetectLanguage middleware in module" || test_fail "DetectLanguage middleware missing"
[ -f "app/Modules/Language/Middleware/LocalizeRoutes.php" ] && test_pass "LocalizeRoutes middleware in module" || test_fail "LocalizeRoutes middleware missing"
[ -f "app/Modules/Language/Middleware/SetLocale.php" ] && test_pass "SetLocale middleware in module" || test_fail "SetLocale middleware missing"
[ -f "app/Modules/Language/Traits/Translatable.php" ] && test_pass "Translatable trait in module" || test_fail "Translatable trait missing"
[ -f "app/Modules/Language/Support/LocalizedRouteMacros.php" ] && test_pass "LocalizedRouteMacros in module" || test_fail "LocalizedRouteMacros missing"

echo ""
echo "2️⃣  No Old Files Left Tests"
echo "----------------------------"

# Test old files are removed
[ ! -f "app/Services/TranslationService.php" ] && test_pass "Old TranslationService removed" || test_fail "Old TranslationService still exists"
[ ! -f "app/Services/LocaleService.php" ] && test_pass "Old LocaleService removed" || test_fail "Old LocaleService still exists"
[ ! -f "app/Http/Controllers/LocaleController.php" ] && test_pass "Old LocaleController removed" || test_fail "Old LocaleController still exists"
[ ! -f "app/Http/Middleware/DetectLanguage.php" ] && test_pass "Old DetectLanguage removed" || test_fail "Old DetectLanguage still exists"
[ ! -f "app/Http/Middleware/LocalizeRoutes.php" ] && test_pass "Old LocalizeRoutes removed" || test_fail "Old LocalizeRoutes still exists"
[ ! -f "app/Http/Middleware/SetLocale.php" ] && test_pass "Old SetLocale removed" || test_fail "Old SetLocale still exists"
[ ! -f "app/Support/Translatable.php" ] && test_pass "Old Translatable removed" || test_fail "Old Translatable still exists"
[ ! -f "app/Support/LocalizedRouteMacros.php" ] && test_pass "Old LocalizedRouteMacros removed" || test_fail "Old LocalizedRouteMacros still exists"

echo ""
echo "3️⃣  File Completeness Tests"
echo "----------------------------"

# Check file sizes (ensure not truncated)
TS_LINES=$(wc -l < app/Modules/Language/Services/TranslationService.php)
[ "$TS_LINES" -gt 300 ] && test_pass "TranslationService complete ($TS_LINES lines)" || test_fail "TranslationService may be truncated ($TS_LINES lines)"

LS_LINES=$(wc -l < app/Modules/Language/Services/LocaleService.php)
[ "$LS_LINES" -gt 100 ] && test_pass "LocaleService complete ($LS_LINES lines)" || test_fail "LocaleService may be truncated ($LS_LINES lines)"

LOC_LINES=$(wc -l < app/Modules/Language/Controllers/LanguageOverrideController.php)
[ "$LOC_LINES" -gt 200 ] && test_pass "LanguageOverrideController complete ($LOC_LINES lines)" || test_fail "LanguageOverrideController may be truncated ($LOC_LINES lines)"

echo ""
echo "4️⃣  Database Tests"
echo "----------------------------"

# Check migration exists
[ -f "database/migrations/"*"_create_language_overrides_table.php" ] && test_pass "Migration file exists" || test_fail "Migration file missing"

# Check table exists
TABLE_EXISTS=$(php artisan tinker --execute="echo \Schema::hasTable('language_overrides') ? 'yes' : 'no';" 2>/dev/null | tail -1)
[ "$TABLE_EXISTS" = "yes" ] && test_pass "Table language_overrides exists" || test_fail "Table language_overrides not found"

echo ""
echo "5️⃣  Route Tests"
echo "----------------------------"

# Check routes exist
php artisan route:list | grep -q "locale.switch" && test_pass "Route locale.switch exists" || test_fail "Route locale.switch missing"
php artisan route:list | grep -q "translations.export" && test_pass "Route translations.export exists" || test_fail "Route translations.export missing"
php artisan route:list | grep -q "admin.translations.index" && test_pass "Route admin.translations.index exists" || test_fail "Route admin.translations.index missing"
php artisan route:list | grep -q "admin.translations.store" && test_pass "Route admin.translations.store exists" || test_fail "Route admin.translations.store missing"

echo ""
echo "6️⃣  Frontend Integration Tests"
echo "----------------------------"

# Check blade component
[ -f "resources/views/components/translations-js.blade.php" ] && test_pass "Blade component translations-js exists" || test_fail "Blade component missing"

# Check alpine-utils update
grep -q "form.action = \`/locale/\${locale}\`" resources/js/alpine-utils.js && test_pass "Alpine language switcher updated" || test_fail "Alpine language switcher not updated"

echo ""
echo "7️⃣  Namespace Tests"
echo "----------------------------"

# Check namespace updates
grep -q "namespace App\\\Modules\\\Language\\\Services" app/Modules/Language/Services/TranslationService.php && test_pass "TranslationService namespace correct" || test_fail "TranslationService namespace wrong"
grep -q "namespace App\\\Modules\\\Language\\\Controllers" app/Modules/Language/Controllers/LocaleController.php && test_pass "LocaleController namespace correct" || test_fail "LocaleController namespace wrong"
grep -q "namespace App\\\Modules\\\Language\\\Middleware" app/Modules/Language/Middleware/DetectLanguage.php && test_pass "DetectLanguage namespace correct" || test_fail "DetectLanguage namespace wrong"

echo ""
echo "8️⃣  Functional Tests"
echo "----------------------------"

# Test TranslationService
TS_TEST=$(php artisan tinker --execute="echo App\Modules\Language\Services\TranslationService::trans('test');" 2>/dev/null | tail -1)
[ -n "$TS_TEST" ] && test_pass "TranslationService::trans() works" || test_fail "TranslationService::trans() failed"

# Test exportToJson
JSON_TEST=$(php artisan tinker --execute="echo strlen(App\Modules\Language\Services\TranslationService::exportToJson('messages'));" 2>/dev/null | tail -1)
[ "$JSON_TEST" -gt 10 ] && test_pass "TranslationService::exportToJson() works" || test_fail "TranslationService::exportToJson() failed"

# Test API endpoint
API_TEST=$(curl -s -o /dev/null -w "%{http_code}" http://192.168.88.44/api/translations/messages)
[ "$API_TEST" = "200" ] && test_pass "API endpoint /api/translations works (HTTP $API_TEST)" || test_fail "API endpoint failed (HTTP $API_TEST)"

echo ""
echo "========================================"
echo "📊 TEST SUMMARY"
echo "========================================"
echo -e "${GREEN}Passed:${NC} $PASS"
echo -e "${RED}Failed:${NC} $FAIL"
echo ""

if [ $FAIL -eq 0 ]; then
    echo -e "${GREEN}✅ ALL TESTS PASSED!${NC}"
    echo "✨ I18n module restructuring successful and fully functional"
    exit 0
else
    echo -e "${RED}❌ SOME TESTS FAILED${NC}"
    echo "⚠️  Please review failed tests above"
    exit 1
fi
