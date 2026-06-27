.PHONY: help install up down reset seed logs healthcheck test clean simple-up

help:
	@echo "Retro Arcade Labs - Commands:"
	@echo "  make simple-up  - Start with 2 containers (PHP + MySQL)"
	@echo "  make up         - Start with all containers (includes proxy/mail)"
	@echo "  make down       - Stop all services"
	@echo "  make reset      - Reset database"
	@echo "  make logs       - View logs"
	@echo "  make healthcheck - Check service health"
	@echo "  make clean      - Remove all containers and volumes"

simple-up:
	docker-compose -f docker-compose.simple.yml up -d

up:
	docker-compose up -d

down:
	docker-compose -f docker-compose.simple.yml down || docker-compose down

reset:
	docker-compose -f docker-compose.simple.yml down -v
	docker-compose -f docker-compose.simple.yml up -d

seed:
	@echo "Waiting for MySQL to be ready..."
	@sleep 10
	docker exec retro-arcade-mysql mysql -uroot -pLOCAL_ONLY_ROOT_PWD retro_arcade < scripts/seed.sql 2>/dev/null || \
	docker exec retro-arcade-mysql mysql -uroot retro_arcade < scripts/seed.sql

logs:
	docker logs -f retro-arcade-php

healthcheck:
	@echo "=== Service Status ===" && \
	docker ps --filter "name=retro-arcade" --format "table {{.Names}}\t{{.Status}}"

test:
	@echo "Testing SQL injection..." && \
	curl -s -X POST http://localhost:8470/api/auth/login.php \
		-H "Content-Type: application/json" \
		-d '{"username":"admin'"'"' --","password":"foo"}' | grep -q "success" && echo "✅ SQLi bypass works" || echo "❌ SQLi failed"

clean:
	docker-compose -f docker-compose.simple.yml down -v --rmi all 2>/dev/null
	docker-compose down -v --rmi all 2>/dev/null
