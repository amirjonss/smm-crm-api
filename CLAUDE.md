# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a CRM API for managing content plans, built with Symfony 7.3, API Platform 4.1, and Doctrine ORM. It uses JWT authentication via Lexik JWT bundle and runs in Docker containers (PHP, Nginx, MariaDB, Redis).

## Common Commands

All commands run inside the PHP Docker container:

```bash
# Enter PHP container
docker compose exec php bash

# Install project (first time setup - waits for DB, runs migrations, generates JWT keys)
docker compose exec php bin/console ask:install

# Deploy (after git pull - runs migrations, clears cache)
docker compose exec php bin/console ask:deploy

# Run database migrations
docker compose exec php bin/console doctrine:migrations:migrate

# Generate a migration after entity changes
docker compose exec php bin/console doctrine:migrations:diff

# Create a new entity
docker compose exec php bin/console make:entity

# Clear cache
docker compose exec php bin/console cache:clear

# Create a user
docker compose exec php bin/console ask:user:create

# Manage user roles
docker compose exec php bin/console ask:roles:add
docker compose exec php bin/console ask:roles:delete
docker compose exec php bin/console ask:roles:show
```

## Architecture

### Entity Patterns

Entities use interface-based traits for automatic timestamp and user tracking:
- `CreatedAtSettableInterface` / `CreatedAtAccessorsTrait` - auto-sets `createdAt` on persist
- `UpdatedAtSettableInterface` / `UpdatedAtAccessorsTrait` - auto-sets `updatedAt` on update
- `CreatedBySettableInterface` / `CreatedByAccessorsTrait` - auto-sets `createdBy` to current user
- `UpdatedBySettableInterface` / `UpdatedByAccessorsTrait` - auto-sets `updatedBy` to current user
- `DeletedBySettableInterface` / `DeletedByAccessorsTrait` - for soft deletes (marks user who deleted)
- `DeletedAtSettableInterface` / `DeletedAtAccessorsTrait` - for soft delete timestamps

Combined traits available: `CreatedUpdatedDeletedAtAndByTrait` includes all tracking fields.

### API Platform Integration

- Entities are API resources via `#[ApiResource]` attributes with operations defined inline
- Custom controllers go in `src/Controller/` and are referenced in operation attributes
- Serialization groups control field visibility (e.g., `user:read`, `user:write`, `project:read`)

### Key Subscribers (Event Listeners)

**WriteSubscriber** (`src/Controller/Subscribers/WriteSubscriber.php`):
- Runs on PRE_WRITE for POST/PATCH requests
- Auto-sets `createdAt`, `createdBy`, `updatedAt`, `updatedBy` based on entity interfaces
- Sets `executor` on Project entities to current user

**ReadExtension** (`src/Controller/Subscribers/ReadExtension.php`):
- Doctrine ORM query extension applied to all collection and item operations
- Hides soft-deleted entities (where `deletedBy` is not null)
- Filters entities by `createdBy` for non-admin users (ContentPlan, Project)
- Admins bypass owner filtering

### Soft Delete Pattern

Entities are not physically deleted. The `DeleteAction` controller marks entities as deleted by setting `deletedBy` to the current user. The `ReadExtension` automatically filters out soft-deleted entities.

### Authentication

- JWT-based authentication using Lexik JWT bundle
- Token endpoints: `POST /api/users/auth` (login), `POST /api/users/auth/refreshToken`
- Token expiration configured via `TOKEN_ACCESS_EXPIRATION_PERIOD` and `TOKEN_REFRESH_EXPIRATION_PERIOD` env vars
- User provider uses email for lookup

### Domain Entities

- **User**: Authentication, roles (ROLE_USER, ROLE_ADMIN), owns Projects
- **Project**: Belongs to executor (User), contains ContentPlans
- **ContentPlan**: Content scheduling with format (Reels, Carousel, Post, Animation, Story), position ordering

## Environment

- Database: MariaDB 11.7 (port configured via `DOCKER_DATABASE_PORT`)
- Web server: Nginx (port configured via `DOCKER_NGINX_PORT`, default 8507)
- API documentation: `http://localhost:8507/api` (Swagger UI)
- Messenger transport: Redis for async message handling

## Rules
- Always use Context7 MCP when I need library/API documentation, code generation, setup or configuration steps without me having to explicitly ask.
