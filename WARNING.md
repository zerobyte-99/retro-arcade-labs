# ⚠️ CRITICAL WARNING - READ BEFORE PROCEEDING ⚠️

## 🚨 RETRO ARCADE LABS IS AN INTENTIONALLY VULNERABLE TRAINING PLATFORM 🚨

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃                                                                              ┃
┃   ██████╗  █████╗ ███╗   ███╗███████╗     ██████╗ ██╗   ██╗███████╗██████╗ ┃
┃  ██╔════╝ ██╔══██╗████╗ ████║██╔════╝    ██╔═══██╗██║   ██║██╔════╝██╔══██╗┃
┃  ██║  ███╗███████║██╔████╔██║█████╗      ██║   ██║██║   ██║█████╗  ██████╔╝┃
┃  ██║   ██║██╔══██║██║╚██╔╝██║██╔══╝      ██║   ██║╚██╗ ██╔╝██╔══╝  ██╔══██╗┃
┃  ╚██████╔╝██║  ██║██║ ╚═╝ ██║███████╗    ╚██████╔╝ ╚████╔╝ ███████╗██║  ██║┃
┃   ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝╚══════╝     ╚═════╝   ╚═══╝  ╚══════╝╚═╝  ╚═╝┃
┃                                                                              ┃
┃                         *** LOCAL USE ONLY ***                               ┃
┃                                                                              ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛
```

---

## ⚠️ ABSOLUTE SAFETY RULES - NON-NEGOTIABLE ⚠️

### 1. LOCAL ENVIRONMENT ONLY
- **This platform MUST only run in a local, isolated environment**
- **Never expose this to the public internet**
- **Never run on a server with real data or real credentials**
- **All services bind to localhost (127.0.0.1) by default**

### 2. INTENTIONALLY VULNERABLE CODE
This application contains **DELIBERATE SECURITY VULNERABILITIES** including:
- SQL Injection (login bypass, data extraction)
- Cross-Site Scripting (XSS)
- XML External Entity (XXE) injection
- Server-Side Request Forgery (SSRF)
- Insecure Direct Object Reference (IDOR)
- Command Injection / RCE
- File Upload vulnerabilities
- Open Redirect vulnerabilities
- Business Logic flaws
- Authentication/Authorization bypasses

### 3. NEVER USE IN PRODUCTION
- **Do not deploy this on any production server**
- **Do not use real passwords, API keys, or credentials**
- **Do not connect to real databases or external services**
- **Do not use any code patterns from this project in production**

### 4. EDUCATIONAL USE ONLY
This platform is designed for:
- Local CTF (Capture The Flag) competitions
- Security lab environments
- Security education and training
- Vulnerability research in controlled settings

---

## 🔒 DOCKER ISOLATION

All vulnerable services are containerized and isolated:
- Network isolation between services
- No external network access required
- Internal services not accessible from host (except via intentional labs)
- SSRF targets limited to internal Docker services only

---

## 🚫 DO NOT

- [ ] **DO NOT** deploy publicly
- [ ] **DO NOT** use real secrets or credentials
- [ ] **DO NOT** connect to real external services
- [ ] **DO NOT** use in a production environment
- [ ] **DO NOT** share code patterns with production applications
- [ ] **DO NOT** ignore security warnings in real applications
- [ ] **DO NOT** use this as a template for secure coding

---

## ✅ DO

- [ ] **DO** use in a local, isolated Docker environment
- [ ] **DO** run security tests and education labs
- [ ] **DO** learn from the vulnerabilities and remediations
- [ ] **DO** practice in a safe, legal environment
- [ ] **DO** understand how attacks work to better defend against them

---

## 📋 LEGAL NOTICE

By using Retro Arcade Labs, you acknowledge that:

1. This is an intentionally vulnerable training platform
2. All vulnerabilities are deliberate and for educational purposes
3. You will only use this in a local, isolated environment
4. You will not attempt to attack systems outside your authorized lab environment
5. You understand that this software should NEVER be deployed publicly

**Unauthorized access to computer systems is illegal. This platform is for authorized education and training only.**

---

## 🆘 If Something Goes Wrong

If you accidentally expose this platform publicly:
1. **Immediately shut down** all services: `make down`
2. **Do not wait** - Assume compromise
3. **Do not use** any passwords or credentials that might have been used
4. **Report** the incident if required by your organization

---

## 📞 Resources

- **Setup Guide**: [docs/SETUP.md](docs/SETUP.md)
- **Vulnerability Matrix**: [docs/VULNERABILITY_MATRIX.md](docs/VULNERABILITY_MATRIX.md)
- **Remediation Guide**: [docs/REMEDIATION_GUIDE.md](docs/REMEDIATION_GUIDE.md)

---

```
╔══════════════════════════════════════════════════════════════════════════════╗
║                                                                              ║
║   "With great power comes great responsibility.                               ║
║    Use this knowledge ethically and legally."                                ║
║                                                                              ║
║   - The Retro Arcade Labs Security Team                                      ║
║                                                                              ║
╚══════════════════════════════════════════════════════════════════════════════╝
```

**Last Updated**: 2026-01-01
**Version**: 1.0.0
**Status**: INTENTIONALLY VULNERABLE - FOR LOCAL USE ONLY