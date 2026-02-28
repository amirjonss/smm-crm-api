DC = docker compose
DC_PHP= $(DC) exec php

.PHONY: lint lint-fix test test-api reset-test-db

lint:
	$(DC_PHP) composer lint:phpstan
	$(DC_PHP) composer lint:fixer:check

lint-fix:
	$(DC_PHP) composer lint:fixer

test:
	$(DC_PHP) bin/phpunit

test-api:
	$(DC_PHP) bin/console doctrine:database:create --if-not-exists --env=test
	$(DC_PHP) bin/console doctrine:migrations:migrate --no-interaction --env=test
	$(DC_PHP) bin/console doctrine:fixtures:load --no-interaction --env=test
	$(DC_PHP) bin/console ask:deploy
	$(DC_PHP) composer test:api

reset-test-db:
	$(DC_PHP) bin/console doctrine:database:drop -f --env=test
	$(DC_PHP) bin/console doctrine:database:create --env=test
	$(DC_PHP) bin/console doctrine:migrations:migrate --allow-no-migration --no-interaction --env=test
	$(DC_PHP) bin/console doctrine:fixtures:load --no-interaction --env=test
