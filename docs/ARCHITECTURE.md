# Retro Arcade Labs - Architecture

## System Overview

```
┌─────────────────────────────────────────────────────────┐
│                    Retro Arcade Labs                     │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌─────────┐    ┌─────────┐    ┌─────────────────────┐  │
│  │  User   │───▶│  Nginx  │───▶│   PHP Application   │  │
│  │ Browser │    │ :8080   │    │      :8470          │  │
│  └─────────┘    └─────────┘    └──────────┬──────────┘  │
│                                            │             │
│        ┌───────────────────────────────────┼──────────┐  │
│        │                                   │          │  │
│        ▼                                   ▼          ▼  │
│  ┌──────────┐   ┌──────────┐    ┌─────────────────────┐ │
│  │  MySQL   │   │  Redis   │    │  Internal Services  │ │
│  │  :3306   │   │  :6379   │    │  - Metadata :8081   │ │
│  └──────────┘   └──────────┘    │  - Mock Mail :8025  │ │
│                                 └─────────────────────┘ │
└─────────────────────────────────────────────────────────┘
```

## Tech Stack

- **Backend**: PHP 8 with vulnerable patterns
- **Frontend**: Server-rendered PHP + Vue.js ready
- **Database**: MySQL 8
- **Cache**: Redis 7
- **Proxy**: Nginx Alpine

## Database Schema

### Users
- id, username, password, email, role, created_at

### Products
- id, name, description, price, category_id, image_url, stock

### Orders
- id, user_id, total, status, created_at

### Cart
- id, user_id, product_id, quantity

### Tickets
- id, user_id, subject, body, status, priority

## Security Notes

All services bind to localhost (127.0.0.1) by default.
Internal services are only accessible via SSRF from the application.
