# KH-AGENCY CRM API

## Project Overview
This directory (`kh-agency-crm-api`) contains the **Backend API** for the KH-AGENCY CRM system. It is built using **Symfony 7.3** and **API Platform 4.1**, serving as the data layer and business logic core for the frontend application.

## Tech Stack
*   **Language:** PHP >= 8.2
*   **Framework:** Symfony 7.3
*   **API Framework:** API Platform 4.1 (REST/Graphql capabilities)
*   **Database:** MariaDB 11.7.2 (MySQL compatible)
*   **ORM:** Doctrine ORM 3.3
*   **Authentication:** Lexik JWT Authentication Bundle 3.1
*   **Caching/Messaging:** Redis 7.4.4
*   **Email (Dev):** Mailpit

## Infrastructure & Docker
The project is containerized using Docker Compose.
*   **`php`**: Main application container (Symfony).
*   **`nginx`**: Web server, exposing the API.
*   **`db`**: MariaDB database.
*   **`redis`**: Caching and message queue.
*   **`mailer`**: Mailpit for capturing development emails.

**Optional Profiles:**
*   **`backup`**: Includes `backup` and `telegram-bot-api` containers for automated database backups.

## Getting Started

### 1. Start Containers
```bash
docker compose up -d
```
*   **API URL:** `http://localhost:8507/api` (Default, check `.env` `DOCKER_NGINX_PORT`)
*   **Mailpit:** `http://localhost:8025`

### 2. Install Dependencies
```bash
docker compose exec php composer install
```

### 3. Project Initialization
This project includes a custom command to handle initial setup (migrations, directory creation, etc.):
```bash
docker compose exec php bin/console ask:install
```

## Key Development Commands

### Common Tasks
*   **Enter PHP Container:** `docker compose exec php bash`
*   **Enter Database Container:** `docker compose exec db bash`
*   **Clear Cache:** `docker compose exec php bin/console cache:clear`
*   **Run Migrations:** `docker compose exec php bin/console doctrine:migrations:migrate`

### Testing & Code Quality
*   **Run Tests:** *Check `composer.json` or `Makefile` (if available).*
*   **Linting/Formatting:** *Standard Symfony practices apply.*

## Directory Structure Highlights
*   **`src/Entity`**: Core domain models (e.g., `User`, `Project`, `ContentPlan`). Note usage of API Platform attributes.
*   **`src/Controller`**: Custom controllers for actions not handled by default API Platform operations.
*   **`src/Command`**: Custom CLI commands, notably `Ask*` commands (e.g., `AskInstallCommand`, `AskUserCreateCommand`).
*   **`config/packages`**: Symfony & Bundle configuration (API Platform, Security, Doctrine).
*   **`migrations`**: Database schema migrations.
*   **`docker/`**: Dockerfile and configuration for specific services (Nginx, PHP, MySQL).

## Conventions
*   **API Platform First:** Prefer using API Platform resources and operations over custom controllers where possible.
*   **Custom Commands:** Setup and admin tasks are often encapsulated in `Ask*` console commands.
*   **Environment Variables:** Managed via `.env` and `.env.local` (Symfony dotenv). Docker specific vars are also in `.env`.
