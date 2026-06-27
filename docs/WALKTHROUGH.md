# Retro Arcade Labs - Walkthrough Guide

## Beginner Labs

### Lab 1: SQL Injection Login Bypass
1. Navigate to http://localhost:8470/pages/login.php
2. Enter username: `admin' --`
3. Enter any password
4. Click "INSERT COIN"
5. **Expected**: Login as admin without valid password

### Lab 2: Reflected XSS
1. Go to product search
2. Enter: `<script>alert('XSS')</script>`
3. **Expected**: Alert dialog appears

### Lab 3: IDOR (View Other Orders)
1. Login as player1
2. View your order (note the order ID)
3. Change the ID in URL to another number
4. **Expected**: Can view other users' orders

## Intermediate Labs

### Lab 4: Stored XSS in Comments
1. Login
2. Go to product detail
3. Submit comment with: `<img src=x onerror=alert('XSS')>`
4. Refresh page
5. **Expected**: XSS executes

### Lab 5: SSRF to Internal Metadata
1. Login as admin
2. Go to Tools > Image Fetcher
3. URL: `http://internal-metadata:8081/api/secrets`
4. **Expected**: Returns mock AWS credentials

### Lab 6: Command Injection
1. Login as admin
2. Go to Admin > Diagnostics
3. Enter: `whoami`
4. **Expected**: Returns current user
5. Try: `;cat /etc/passwd`

### Lab 7: Business Logic - Price Manipulation
1. Add items to cart
2. Go to checkout
3. Use browser dev tools to change `total_price` value
4. Submit order
5. **Expected**: Order created with modified price

## Advanced Labs

### Lab 8: Mass Assignment Privilege Escalation
1. Login as regular user
2. Capture profile update request
3. Add `role=admin` to the request body
4. **Expected**: Role upgraded to admin

### Lab 9: Race Condition (Coupon Reuse)
1. Get a single-use coupon code
2. Send concurrent requests to apply it
3. **Expected**: Coupon used multiple times

### Lab 10: XXE in File Upload
1. Login as admin
2. Create XML file with: `<?xml version="1.0"?><!DOCTYPE foo [<!ENTITY xxe SYSTEM "file:///etc/passwd">]><data>&xxe;</data>`
3. Upload via avatar upload
4. **Expected**: File contents exposed

## Remediation

See [REMEDIATION_GUIDE.md](REMEDIATION_GUIDE.md) for secure code examples.
