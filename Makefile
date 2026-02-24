DC = docker compose

lint:
	$(DC) exec php composer lint:phpstan
	$(DC) exec php composer lint:fixer:check

lint-fix:
	$(DC) exec php composer lint:fixer

test:
	$(DC) exec php bin/phpunit
