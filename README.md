# KH Agency CRM API

Backend API для CRM-части KH Agency: управление пользователями, проектами, контент-планами, досками, списками и карточками.

## Стек

- PHP `8.2+`
- Symfony `7.3`
- API Platform `4.1`
- Doctrine ORM + Migrations
- MariaDB `11`
- Redis `7` (Messenger/кеш)
- JWT (LexikJWTAuthenticationBundle)
- Docker Compose

## Основные сущности

- `User`
- `Project`
- `ContentPlan`
- `ContentPlanPlatform`
- `Board`
- `BoardList`
- `Card`
- `CardLog`
- `CardPattern`

## Быстрый старт

1. Поднять контейнеры:

```bash
docker compose up -d --build
```

2. Установить зависимости:

```bash
docker compose exec php composer install
```

3. Выполнить первичную установку:

```bash
docker compose exec php bin/console ask:install
```

Команда `ask:install`:
- ожидает доступность БД;
- запускает `ask:deploy` (миграции, кеш и т.д.);
- генерирует JWT-ключи (`ask:generate:jwtKeys`).

После запуска API доступен по адресу:

- `http://localhost:8512/api` (по умолчанию)

Порт настраивается через `DOCKER_NGINX_PORT` в `.env`.

## Тестовые пользователи (fixtures)

- `admin@example.com` / `passwd` (`ROLE_ADMIN`)
- `smm@example.com` / `passwd` (`ROLE_SMM`)
- `smm2@example.com` / `passwd` (`ROLE_SMM`)
- `operator@example.com` / `passwd` (`ROLE_OPERATOR`)

JWT авторизация:

- `POST /api/users/auth`
- `POST /api/users/auth-by-refresh-token`

## Полезные команды

Запуск shell в контейнере:

```bash
docker compose exec php bash
```

Повторный деплой локально после `git pull`:

```bash
docker compose exec php bin/console ask:deploy
```

Линтинг:

```bash
make lint
```

Автофикс стиля:

```bash
make lint-fix
```

API тесты:

```bash
make test-api
```

Сброс тестовой БД:

```bash
make reset-test-db
```

Заполнение БД демо-данными:

```bash
docker compose exec php bin/console app:seed-database
```

Отправка контент-планов за сегодня в Telegram:

```bash
docker compose exec php bin/console app:send-daily-content-plans --chatId=<CHAT_ID>
```

Webhook для запуска отправки:

- `POST /api/webhook/content-plans/send-daily`

## Docker и окружение

Сервисы в `docker-compose.yml`:

- `php`
- `nginx`
- `db` (MariaDB)
- `redis`
- `mailer` (Mailpit)
- `backup` + `telegram-bot-api` (профиль `backup`)

Параметры окружения по умолчанию:

- `DOCKER_PROJECT_NAME=kh-agency-api`
- `DOCKER_NGINX_PORT=8512`
- `DOCKER_DATABASE_PORT=3512`

## Backup

Включить backup-профиль:

```bash
docker compose --profile backup up -d
```

Ручной запуск бэкапа:

```bash
docker compose exec backup /usr/local/bin/backup.sh
```

## CI

Pipeline (`.gitlab-ci.yml`) включает стадии:

- `build`
- `lint`
- `test`
- `deploy`

Проверки:

- `php-cs-fixer`
- `phpstan`
- API tests (`make test-api`)
