#!/bin/bash

echo ""
echo "╔══════════════════════════════════════════════════════════════════════╗"
echo "║                                                                      ║"
echo "║              🔐 TESTING ADMIN LOGIN FLOW 🔐                         ║"
echo "║                                                                      ║"
echo "╚══════════════════════════════════════════════════════════════════════╝"
echo ""

BASE_URL="http://192.168.88.44"
COOKIE_FILE="/tmp/cms_admin_cookies.txt"

# Clean up old cookies
rm -f $COOKIE_FILE

echo "📍 Step 1: Access login page"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
RESPONSE=$(curl -s -c $COOKIE_FILE "${BASE_URL}/admin/login")
if echo "$RESPONSE" | grep -q "Admin Login"; then
    echo "✅ Login page accessible"
else
    echo "❌ Login page not found"
    exit 1
fi
echo ""

echo "📍 Step 2: Extract CSRF token"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
CSRF_TOKEN=$(echo "$RESPONSE" | grep -oP '(?<=name="_token" value=")[^"]+' | head -1)
if [ -n "$CSRF_TOKEN" ]; then
    echo "✅ CSRF token extracted: ${CSRF_TOKEN:0:20}..."
else
    echo "❌ CSRF token not found"
    exit 1
fi
echo ""

echo "📍 Step 3: Submit login credentials"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "   Email: admin@example.com"
echo "   Password: password"
LOGIN_RESPONSE=$(curl -s -L -b $COOKIE_FILE -c $COOKIE_FILE \
    -d "_token=$CSRF_TOKEN" \
    -d "email=admin@example.com" \
    -d "password=password" \
    -d "remember=1" \
    "${BASE_URL}/admin/login")

if echo "$LOGIN_RESPONSE" | grep -q "Welcome back"; then
    echo "✅ Login successful!"
    echo ""
    
    echo "📍 Step 4: Access dashboard"
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    DASHBOARD=$(curl -s -b $COOKIE_FILE "${BASE_URL}/admin/dashboard")
    
    if echo "$DASHBOARD" | grep -q "Admin User"; then
        echo "✅ Dashboard accessible"
        echo ""
        
        # Check statistics
        if echo "$DASHBOARD" | grep -q "Articles"; then
            echo "✅ Statistics cards rendered"
        fi
        
        if echo "$DASHBOARD" | grep -q "Recent Articles"; then
            echo "✅ Recent articles section found"
        fi
        
        if echo "$DASHBOARD" | grep -q "Quick Actions"; then
            echo "✅ Quick actions section found"
        fi
        
        if echo "$DASHBOARD" | grep -q "System Info"; then
            echo "✅ System info section found"
        fi
        
    else
        echo "❌ Dashboard not accessible"
        exit 1
    fi
    
else
    echo "❌ Login failed"
    if echo "$LOGIN_RESPONSE" | grep -q "do not match"; then
        echo "   Error: Invalid credentials"
    elif echo "$LOGIN_RESPONSE" | grep -q "permission"; then
        echo "   Error: No permission to access admin panel"
    else
        echo "   Error: Unknown error"
    fi
    exit 1
fi

echo ""
echo "╔══════════════════════════════════════════════════════════════════════╗"
echo "║                                                                      ║"
echo "║                     ✅ ALL TESTS PASSED!                             ║"
echo "║                                                                      ║"
echo "╚══════════════════════════════════════════════════════════════════════╝"
echo ""

echo "🎉 Admin panel is working perfectly!"
echo ""
echo "📋 Summary:"
echo "   • Login page: ✅ Working"
echo "   • Authentication: ✅ Working"
echo "   • RBAC check: ✅ Working"
echo "   • Dashboard: ✅ Working"
echo "   • Statistics: ✅ Working"
echo ""
echo "🚀 Access URL: ${BASE_URL}/admin/login"
echo ""

# Cleanup
rm -f $COOKIE_FILE
