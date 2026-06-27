# Retro Arcade Labs - API Reference

## Authentication

### POST /api/auth/login.php
Login with username/password (SQL injection vulnerable).

**Request:**
```json
{
  "username": "admin' --",
  "password": "anything"
}
```

**Response:**
```json
{
  "success": true,
  "user": {
    "id": 6,
    "username": "admin@example.local",
    "role": "admin"
  }
}
```

## Products

### GET /api/products/list.php?category=X
List products (SQL injection in category filter).

### GET /api/products/search.php?q=X
Search products (SQL injection vulnerable).

## Orders

### GET /api/orders/view.php?id=X
View order (IDOR - no ownership check).

## Users

### GET /api/users/profile.php
Get current user profile.

### PUT /api/users/profile.php
Update profile (CSRF + Mass Assignment vulnerable).

## Tools

### GET /api/tools/image-fetch.php?url=X
Fetch image from URL (SSRF vulnerable).

### POST /api/tools/webhook-test
Send webhook request (SSRF vulnerable).

### GET /api/tools/report.php?type=X
Generate report (Command injection vulnerable).

## Upload

### POST /api/upload/avatar.php
Upload avatar (Path traversal + weak validation vulnerable).

## Health

### GET /api/health.php
Health check endpoint.
