# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

CRM API for managing Trello-style boards/cards and content plans. Built with Symfony 7.3, API Platform 4.1, Doctrine ORM 3.3, JWT auth (Lexik). Runs in Docker (PHP, Nginx, MariaDB 11.7, Redis).

## Common Commands

All commands run inside the PHP container (`docker compose exec php <command>`):

```bash
bin/console ask:install                    # First-time setup (waits for DB, migrations, JWT keys)
bin/console ask:deploy                     # Post-deploy (migrations + cache clear)
bin/console doctrine:migrations:diff       # Generate migration after entity changes
bin/console doctrine:migrations:migrate    # Run migrations
bin/console cache:clear
bin/console ask:create-user                # Interactive user creation
bin/console seed:database                  # Seed test data
```

API docs: `http://localhost:8507/api` (Swagger UI, port via `DOCKER_NGINX_PORT`)

## Architecture

### Entity Lifecycle — Interface + Trait Pattern

Entities implement settable interfaces; `WriteSubscriber` (PRE_WRITE) auto-fills fields:

- `CreatedAtSettableInterface` / `CreatedAtAccessorsTrait` — set on POST
- `CreatedBySettableInterface` / `CreatedByAccessorsTrait` — set on POST (current user)
- `UpdatedAtSettableInterface` / `UpdatedAtAccessorsTrait` — set on PATCH
- `UpdatedBySettableInterface` / `UpdatedByAccessorsTrait` — set on PATCH (current user)
- `DeletedAtSettableInterface` / `DeletedAtAccessorsTrait` — set by `DeleteAction`
- `DeletedBySettableInterface` / `DeletedByAccessorsTrait` — set by `DeleteAction`

Shortcut: `CreatedUpdatedDeletedAtAndByTrait` combines all six.

### Soft Delete

Entities are never physically deleted. `DeleteAction` (`src/Controller/DeleteAction.php`) uses `MarkEntityAsDeleted` to set `deletedBy`/`deletedAt`. `ReadExtension` (Doctrine query extension) auto-filters `deletedBy IS NOT NULL` from all queries. Non-admin users additionally see only entities where `createdBy = current user`.

### Key Subscribers

- **WriteSubscriber** (`src/Controller/Subscribers/WriteSubscriber.php`) — PRE_WRITE: auto-sets timestamp/user tracking fields on POST and PATCH
- **ReadExtension** (`src/Controller/Subscribers/ReadExtension.php`) — Doctrine query extension: soft-delete filtering + owner-based access control (admins bypass)
- **CardRenameSubscriber** (`src/Controller/Subscribers/CardRenameSubscriber.php`) — POST_WRITE on Card PATCH: detects field changes (rename, move, deadline, archive/unarchive) and dispatches corresponding events

### Card Event System

Events (`src/Event/Card/`) dispatched by `CardRenameSubscriber` and `CreateCardAction`. Each event has a listener (`src/EventListener/Card/`) that creates a `CardLog` entry via `CardLogFactory` + `CardLogManager`. Provides full audit trail: `CardCreatedEvent`, `CardRenamedEvent`, `CardMovedEvent`, `CardSetDeadlineEvent`, `CardChangedDeadlineEvent`, `CardDeleteDeadlineEvent`, `CardArchivedEvent`, `CardUnarchivedEvent`.

### Component Layer (`src/Component/`)

Business logic organized by domain:
- **Core/** — `AbstractManager` (base entity persistence with flush), `MarkEntityAsDeleted`, `SlugGenerator`
- **User/** — `UserFactory`, `UserManager` (password hashing), `TokensCreator` (JWT), `CurrentUser`, roles enum, DTOs
- **CardLog/** — `CardLogFactory`, `CardLogManager`
- **Board/** — `CardStatus` enum (`open`, `in_progress`, `review`, `done`)

### Base Controller

`AbstractController` (`src/Controller/Base/AbstractController.php`) provides: `response()`, `responseEmpty()`, `getDtoFromRequest()`, `getUser()`, `getJwtUser()`, `validate()`, `findEntityOrError()`. All custom action controllers extend this.

### Domain Entities

- **User** → owns Projects (executor), assigned to Cards (executors ManyToMany)
- **Project** → belongs to User, contains ContentPlans
- **Board** → contains BoardLists (ordered by position)
- **BoardList** → belongs to Board, contains Cards (ordered by position)
- **Card** → belongs to BoardList, has CardLogs (audit) and executors (Users), status enum, deadline, archive flag
- **CardLog** → belongs to Card, read-only audit log created by event listeners
- **ContentPlan** → belongs to Project, has ContentPlanPlatforms (YouTube, Instagram, Facebook, Telegram)

### Roles

Defined in `Component/User/Enum/Roles`: `ROLE_SMM`, `ROLE_EDITOR`, `ROLE_DESIGNER`, `ROLE_OPERATOR`, `ROLE_ADMIN`, `ROLE_USER`. Board/Card operations require ADMIN or SMM. User management requires ADMIN.

### Authentication

JWT via Lexik bundle. `POST /api/users/auth` (login), `POST /api/users/auth/refreshToken`. Token expiry via `TOKEN_ACCESS_EXPIRATION_PERIOD` / `TOKEN_REFRESH_EXPIRATION_PERIOD` env vars. Main firewall is stateless JWT for `/api/.+`.

### API Platform Conventions

- Entities as `#[ApiResource]` with inline operations and `#[ApiFilter]` (Order, Search, Boolean, Date)
- Serialization groups per operation (e.g., `card:read`, `card:write`, `card:post:write`)
- Sub-resource URIs via `uriTemplate` + `uriVariables` with `Link` (e.g., `/cards/{cardId}/executors`)
- Format: JSON-LD primary, multipart supported

## Environment

- Docker services: php, nginx (port 8507), db (MariaDB), redis, mailer (Mailpit)
- Optional profiles: `backup` (DB backup to Telegram), `telegram-bot-api`
- Cron jobs: `docker/php/cron-file` (rebuild php container after changes)

## Rules

- Always use Context7 MCP when I need library/API documentation, code generation, setup or configuration steps without me having to explicitly ask.
