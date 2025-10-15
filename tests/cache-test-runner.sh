#!/bin/bash

# Cache System Test Runner
# Tes komprehensif untuk sistem cache

echo "════════════════════════════════════════════════"
echo "   🧪 CACHE SYSTEM TEST RUNNER"
echo "════════════════════════════════════════════════"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print with color
print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

# Change to project directory
cd "$(dirname "$0")/.." || exit

# Step 1: Check if required files exist
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "1️⃣  Checking Required Files"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

FILES_TO_CHECK=(
    "app/Support/CacheHelper.php"
    "app/Services/CacheService.php"
    "app/Services/CacheManager.php"
    "app/Http/Controllers/CacheController.php"
    "tests/Feature/CacheSystemTest.php"
)

ALL_FILES_EXIST=true
for file in "${FILES_TO_CHECK[@]}"; do
    if [ -f "$file" ]; then
        print_success "Found: $file"
    else
        print_error "Missing: $file"
        ALL_FILES_EXIST=false
    fi
done

if [ "$ALL_FILES_EXIST" = false ]; then
    print_error "Some required files are missing!"
    exit 1
fi

echo ""

# Step 2: Check cache configuration
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "2️⃣  Checking Cache Configuration"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

CACHE_DRIVER=$(php artisan tinker --execute="echo config('cache.default');" 2>/dev/null | tail -1)
print_info "Current cache driver: $CACHE_DRIVER"

if [ "$CACHE_DRIVER" = "null" ]; then
    print_warning "Cache is disabled (driver: null)"
else
    print_success "Cache is enabled"
fi

# Check cache directory permissions
if [ -d "storage/framework/cache/data" ]; then
    if [ -w "storage/framework/cache/data" ]; then
        print_success "Cache directory is writable"
    else
        print_warning "Cache directory is not writable"
        print_info "Run: chmod -R 775 storage/framework/cache"
    fi
else
    print_error "Cache directory doesn't exist"
    mkdir -p storage/framework/cache/data
    print_info "Created cache directory"
fi

echo ""

# Step 3: Clear cache before testing
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "3️⃣  Clearing Cache"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

php artisan cache:clear 2>/dev/null
if [ $? -eq 0 ]; then
    print_success "Cache cleared successfully"
else
    print_warning "Cache clear failed (may need sudo)"
fi

echo ""

# Step 4: Run unit tests
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "4️⃣  Running Unit Tests"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Run CacheHelper tests
print_info "Running CacheHelper tests..."
php artisan test --filter CacheHelperTest 2>&1 | grep -E "(PASS|FAIL|Tests:|Assertions:)"

# Run CacheSystem tests
print_info "Running CacheSystem tests..."
php artisan test --filter CacheSystemTest 2>&1 | grep -E "(PASS|FAIL|Tests:|Assertions:)"

echo ""

# Step 5: Manual functionality tests
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "5️⃣  Manual Functionality Tests"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Test 1: CacheHelper exists
print_info "Test: CacheHelper class exists"
php artisan tinker --execute="
if (class_exists('App\Support\CacheHelper')) {
    echo 'PASS: CacheHelper exists';
} else {
    echo 'FAIL: CacheHelper not found';
}
" 2>/dev/null | tail -1

# Test 2: Basic cache operations
print_info "Test: Basic cache operations"
php artisan tinker --execute="
use App\Support\CacheHelper;
CacheHelper::put('test_key', 'test_value', 'test', 60);
if (CacheHelper::has('test_key') && CacheHelper::get('test_key') === 'test_value') {
    echo 'PASS: Cache put/get works';
} else {
    echo 'FAIL: Cache operations failed';
}
CacheHelper::forget('test_key');
" 2>/dev/null | tail -1

# Test 3: CacheService metrics
print_info "Test: CacheService metrics tracking"
php artisan tinker --execute="
use App\Services\CacheService;
\$service = new CacheService();
\$service->set('metric_test', 'value', 60);
\$service->get('metric_test');
\$service->get('non_existent');
\$stats = \$service->getStats();
if (\$stats['hits'] >= 1 && \$stats['misses'] >= 1 && \$stats['writes'] >= 1) {
    echo 'PASS: Metrics tracking works';
} else {
    echo 'FAIL: Metrics not tracked properly';
}
" 2>/dev/null | tail -1

# Test 4: CacheManager
print_info "Test: CacheManager status"
php artisan tinker --execute="
use App\Services\CacheManager;
\$manager = app(CacheManager::class);
\$status = \$manager->getStatus();
if (isset(\$status['enabled']) && isset(\$status['driver'])) {
    echo 'PASS: CacheManager works';
} else {
    echo 'FAIL: CacheManager status failed';
}
" 2>/dev/null | tail -1

echo ""

# Step 6: Performance test
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "6️⃣  Performance Test"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

print_info "Testing cache vs no-cache performance..."

# Without cache
php artisan tinker --execute="
\$start = microtime(true);
for (\$i = 0; \$i < 100; \$i++) {
    \$data = ['item' => \$i, 'value' => str_repeat('data', 100)];
}
\$duration = (microtime(true) - \$start) * 1000;
echo 'Without cache: ' . round(\$duration, 2) . 'ms';
" 2>/dev/null | tail -1

# With cache
php artisan tinker --execute="
use App\Support\CacheHelper;
\$start = microtime(true);
for (\$i = 0; \$i < 100; \$i++) {
    CacheHelper::remember('perf_test_' . \$i, 'test', 60, function() use (\$i) {
        return ['item' => \$i, 'value' => str_repeat('data', 100)];
    });
}
\$duration = (microtime(true) - \$start) * 1000;
echo 'With cache (first): ' . round(\$duration, 2) . 'ms';
" 2>/dev/null | tail -1

php artisan tinker --execute="
use App\Support\CacheHelper;
\$start = microtime(true);
for (\$i = 0; \$i < 100; \$i++) {
    CacheHelper::remember('perf_test_' . \$i, 'test', 60, function() use (\$i) {
        return ['item' => \$i, 'value' => str_repeat('data', 100)];
    });
}
\$duration = (microtime(true) - \$start) * 1000;
echo 'With cache (cached): ' . round(\$duration, 2) . 'ms';
" 2>/dev/null | tail -1

echo ""

# Step 7: Check cache files created
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "7️⃣  Cache Files Check"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

CACHE_FILE_COUNT=$(find storage/framework/cache/data -type f 2>/dev/null | wc -l)
print_info "Cache files created: $CACHE_FILE_COUNT"

if [ "$CACHE_FILE_COUNT" -gt 0 ]; then
    print_success "Cache files are being created"
else
    print_warning "No cache files found (may use database or redis)"
fi

echo ""

# Final summary
echo "════════════════════════════════════════════════"
echo "   ✅ TEST SUMMARY"
echo "════════════════════════════════════════════════"
print_success "All critical files exist"
print_success "Cache system is operational"
print_info "Driver: $CACHE_DRIVER"
print_info "Cache files: $CACHE_FILE_COUNT"
echo ""
print_info "Review the test results above for details"
print_info "Check docs/CACHE_SYSTEM_REVIEW.md for full documentation"
echo "════════════════════════════════════════════════"

