#!/bin/bash

# ============================================================================
# I18n CMS-Style Features Test Script
# Tests WordPress, Joomla, Drupal-style implementations
# ============================================================================

echo "🌐 Testing I18n CMS-Style Features..."
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
        echo -e "${GREEN}✓${NC} $1"
        ((PASSED++))
    else
        echo -e "${RED}✗${NC} $1"
        ((FAILED++))
    fi
}

# Change to cms-app directory
cd "$(dirname "$0")/../.." || exit 1

# ============================================================================
# Phase 1: Check Files Exist
# ============================================================================
echo "📁 Checking Files..."
echo "--------------------"

[ -f "app/Services/TranslationService.php" ]
test_check "TranslationService.php exists"

[ -f "app/Modules/Language/Models/LanguageOverride.php" ]
test_check "LanguageOverride.php model exists (in Language module)"

[ -f "app/Support/helpers.php" ]
test_check "helpers.php exists"

[ -f "docs/I18N_BEST_PRACTICES_CMS_COMPARISON.md" ]
test_check "Best Practices documentation exists"

[ -f "docs/I18N_QUICK_REFERENCE_CMS_STYLE.md" ]
test_check "Quick Reference documentation exists"

[ -f "docs/I18N_ENHANCEMENT_SUMMARY.md" ]
test_check "Enhancement Summary exists"

echo ""

# ============================================================================
# Phase 2: Check Helper Functions
# ============================================================================
echo "🔧 Checking Helper Functions..."
echo "--------------------------------"

grep -q "function _t" app/Support/helpers.php
test_check "_t() function exists"

grep -q "function _e" app/Support/helpers.php
test_check "_e() function exists"

grep -q "function _n" app/Support/helpers.php
test_check "_n() function exists"

grep -q "function _x" app/Support/helpers.php
test_check "_x() function exists"

grep -q "function esc_html__" app/Support/helpers.php
test_check "esc_html__() function exists"

grep -q "function esc_attr__" app/Support/helpers.php
test_check "esc_attr__() function exists"

grep -q "function JText" app/Support/helpers.php
test_check "JText() function exists (Joomla-style)"

grep -q "function trans_domain" app/Support/helpers.php
test_check "trans_domain() function exists"

grep -q "function trans_missing" app/Support/helpers.php
test_check "trans_missing() function exists"

grep -q "function trans_stats" app/Support/helpers.php
test_check "trans_stats() function exists"

grep -q "function trans_export_js" app/Support/helpers.php
test_check "trans_export_js() function exists"

grep -q "function trans_clear_cache" app/Support/helpers.php
test_check "trans_clear_cache() function exists"

echo ""

# ============================================================================
# Phase 3: Check TranslationService Methods
# ============================================================================
echo "⚙️  Checking TranslationService..."
echo "----------------------------------"

grep -q "public static function trans" app/Services/TranslationService.php
test_check "trans() method exists"

grep -q "protected static function loadDomain" app/Services/TranslationService.php
test_check "loadDomain() method exists"

grep -q "protected static function getOverride" app/Services/TranslationService.php
test_check "getOverride() method exists (Joomla-style)"

grep -q "public static function setOverride" app/Services/TranslationService.php
test_check "setOverride() method exists"

grep -q "public static function removeOverride" app/Services/TranslationService.php
test_check "removeOverride() method exists"

grep -q "protected static function trackMissing" app/Services/TranslationService.php
test_check "trackMissing() method exists (Drupal-style)"

grep -q "public static function getMissingTranslations" app/Services/TranslationService.php
test_check "getMissingTranslations() method exists"

grep -q "public static function getStatistics" app/Services/TranslationService.php
test_check "getStatistics() method exists"

grep -q "public static function clearCache" app/Services/TranslationService.php
test_check "clearCache() method exists"

grep -q "public static function preload" app/Services/TranslationService.php
test_check "preload() method exists"

grep -q "public static function exportToJson" app/Services/TranslationService.php
test_check "exportToJson() method exists"

echo ""

# ============================================================================
# Phase 4: Check LanguageOverride Model
# ============================================================================
echo "💾 Checking LanguageOverride Model..."
echo "-------------------------------------"

grep -q "class LanguageOverride extends Model" app/Modules/Language/Models/LanguageOverride.php
test_check "LanguageOverride model class exists"

grep -q "namespace App\\\\Modules\\\\Language\\\\Models" app/Modules/Language/Models/LanguageOverride.php
test_check "LanguageOverride has correct namespace"

grep -q "public function scopeActive" app/Modules/Language/Models/LanguageOverride.php
test_check "scopeActive() exists"

grep -q "public function scopeForLocale" app/Modules/Language/Models/LanguageOverride.php
test_check "scopeForLocale() exists"

grep -q "public function scopeForDomain" app/Modules/Language/Models/LanguageOverride.php
test_check "scopeForDomain() exists"

grep -q "public static function getOverrides" app/Modules/Language/Models/LanguageOverride.php
test_check "getOverrides() method exists"

grep -q "public static function setOverride" app/Modules/Language/Models/LanguageOverride.php
test_check "setOverride() method exists"

grep -q "public static function removeOverride" app/Modules/Language/Models/LanguageOverride.php
test_check "removeOverride() method exists"

echo ""

# ============================================================================
# Phase 5: Check Migration
# ============================================================================
echo "🗄️  Checking Migration..."
echo "-------------------------"

MIGRATION_FILE=$(find database/migrations -name "*create_language_overrides_table.php" | head -n 1)

if [ -f "$MIGRATION_FILE" ]; then
    echo -e "${GREEN}✓${NC} Migration file exists"
    ((PASSED++))
    
    grep -q "locale" "$MIGRATION_FILE"
    test_check "  - locale column defined"
    
    grep -q "domain" "$MIGRATION_FILE"
    test_check "  - domain column defined"
    
    grep -q "key" "$MIGRATION_FILE"
    test_check "  - key column defined"
    
    grep -q "value" "$MIGRATION_FILE"
    test_check "  - value column defined"
    
    grep -q "original_value" "$MIGRATION_FILE"
    test_check "  - original_value column defined"
    
    grep -q "status" "$MIGRATION_FILE"
    test_check "  - status column defined"
    
    grep -q "override_unique" "$MIGRATION_FILE"
    test_check "  - unique index defined"
else
    echo -e "${RED}✗${NC} Migration file NOT found"
    ((FAILED++))
fi

echo ""

# ============================================================================
# Phase 6: Documentation Quality Check
# ============================================================================
echo "📚 Checking Documentation..."
echo "----------------------------"

# Check Best Practices doc
if [ -f "docs/I18N_BEST_PRACTICES_CMS_COMPARISON.md" ]; then
    grep -q "WordPress" docs/I18N_BEST_PRACTICES_CMS_COMPARISON.md
    test_check "WordPress section in Best Practices"
    
    grep -q "Joomla" docs/I18N_BEST_PRACTICES_CMS_COMPARISON.md
    test_check "Joomla section in Best Practices"
    
    grep -q "Drupal" docs/I18N_BEST_PRACTICES_CMS_COMPARISON.md
    test_check "Drupal section in Best Practices"
    
    grep -q "PrestaShop" docs/I18N_BEST_PRACTICES_CMS_COMPARISON.md
    test_check "PrestaShop section in Best Practices"
    
    grep -q "Performance Optimization" docs/I18N_BEST_PRACTICES_CMS_COMPARISON.md
    test_check "Performance section in Best Practices"
fi

# Check Quick Reference
if [ -f "docs/I18N_QUICK_REFERENCE_CMS_STYLE.md" ]; then
    grep -q "_t()" docs/I18N_QUICK_REFERENCE_CMS_STYLE.md
    test_check "_t() documented in Quick Reference"
    
    grep -q "Code Examples" docs/I18N_QUICK_REFERENCE_CMS_STYLE.md
    test_check "Code Examples section exists"
    
    grep -q "Best Practices" docs/I18N_QUICK_REFERENCE_CMS_STYLE.md
    test_check "Best Practices section exists"
fi

echo ""

# ============================================================================
# Phase 7: Code Quality Checks
# ============================================================================
echo "✨ Code Quality Checks..."
echo "-------------------------"

# Check for proper namespacing
grep -q "namespace App\\\\Services" app/Services/TranslationService.php
test_check "TranslationService has proper namespace"

grep -q "namespace App\\\\Modules\\\\Language\\\\Models" app/Modules/Language/Models/LanguageOverride.php
test_check "LanguageOverride has proper namespace"

# Check for docblocks
grep -q "/\*\*" app/Services/TranslationService.php
test_check "TranslationService has docblocks"

grep -q "/\*\*" app/Modules/Language/Models/LanguageOverride.php
test_check "LanguageOverride has docblocks"

# Check for type hints
grep -q "string \$key" app/Services/TranslationService.php
test_check "TranslationService uses type hints"

grep -q "string \$locale" app/Modules/Language/Models/LanguageOverride.php
test_check "LanguageOverride uses type hints"

# Check controller exists
[ -f "app/Modules/Language/Controllers/LanguageOverrideController.php" ]
test_check "LanguageOverrideController exists"

echo ""

# ============================================================================
# Summary
# ============================================================================
echo "========================================"
echo "📊 Test Summary"
echo "========================================"
echo -e "${GREEN}Passed: $PASSED${NC}"
echo -e "${RED}Failed: $FAILED${NC}"
echo ""

TOTAL=$((PASSED + FAILED))
if [ $TOTAL -gt 0 ]; then
    PERCENTAGE=$((PASSED * 100 / TOTAL))
    echo "Success Rate: ${PERCENTAGE}%"
fi

echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}✅ All tests passed! I18n CMS-style implementation is complete!${NC}"
    echo ""
    echo "🚀 Next Steps:"
    echo "  1. Run: php artisan migrate (to create language_overrides table)"
    echo "  2. Read: docs/I18N_QUICK_REFERENCE_CMS_STYLE.md"
    echo "  3. Start using: _t('key', 'domain') in your code"
    echo ""
    exit 0
else
    echo -e "${YELLOW}⚠️  Some tests failed. Please review the implementation.${NC}"
    echo ""
    exit 1
fi
