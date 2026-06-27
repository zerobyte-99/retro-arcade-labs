# Retro Arcade Labs 🎮

## 🚨 WARNING: INTENTIONALLY VULNERABLE - LOCAL USE ONLY 🚨

**This platform is for LOCAL CTF, lab, and security education ONLY.**

DO NOT deploy publicly. All services bind to localhost (127.0.0.1).

---

## Quick Start (2 Containers)

```bash
# Start PHP + MySQL only
make simple-up

# Seed the database (wait ~10 seconds after starting)
make seed

# Access the application
open http://localhost:8470
```

---

## Default Accounts

| Username | Password | Role |
|----------|----------|------|
| guest@example.local | Password123! | Guest |
| player1@example.local | Password123! | Player |
| premium@example.local | Password123! | Premium |
| moderator@example.local | Password123! | Moderator |
| admin@example.local | AdminPassword123! | Admin |

---

## Commands

```bash
make simple-up   # Start 2-container setup (recommended)
make up          # Start full setup with proxy/mail
make down        # Stop services
make reset       # Reset database
make logs        # View logs
make test        # Test SQL injection
make clean       # Remove everything
```

---

## Try the SQL Injection!

```bash
# Login as admin without password:
curl -X POST http://localhost:8470/api/auth/login.php \
  -H "Content-Type: application/json" \
  -d '{"username":"admin'"'"' --","password":"foo"}'
```

---

## Documentation

- [WARNING.md](WARNING.md) - Critical safety info
- [docs/VULNERABILITY_MATRIX.md](docs/VULNERABILITY_MATRIX.md) - All vulnerabilities
- [docs/WALKTHROUGH.md](docs/WALKTHROUGH.md) - Learning path
- [docs/SETUP.md](docs/SETUP.md) - Detailed setup

---

**INSERT COIN TO CONTINUE...** 🎮
