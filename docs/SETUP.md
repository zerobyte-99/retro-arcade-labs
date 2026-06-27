# Retro Arcade Labs - Setup Guide

## Prerequisites

- Docker & Docker Compose installed
- 4GB RAM available
- Ports 3306, 6379, 8025, 8080, 8470 available

## Quick Start

```bash
# Navigate to project
cd retro-arcade-labs

# Start all services
make up

# Seed the database
make seed

# Access the application
open http://localhost:8470
```

## Services

| Service | Port | Description |
|---------|------|-------------|
| Web App | 8470 | Main PHP application |
| MySQL | 3306 | Database |
| Redis | 6379 | Sessions/Cache |
| Nginx | 8080 | Reverse proxy |
| Mock Mail | 8025 | Mail catcher UI |

## Default Accounts

| Username | Password | Role |
|----------|----------|------|
| guest@example.local | Password123! | Guest |
| player1@example.local | Password123! | Player |
| premium@example.local | Password123! | Premium |
| moderator@example.local | Password123! | Moderator |
| admin@example.local | AdminPassword123! | Admin |

## Troubleshooting

### Database Connection Errors
```bash
make reset
make seed
```

### Port Already in Use
```bash
# Find and kill process using port
lsof -i :8470
```

### View Logs
```bash
make logs
```

## Reset

```bash
make reset  # Stops and removes data
make up     # Restart fresh
make seed   # Re-seed
```
