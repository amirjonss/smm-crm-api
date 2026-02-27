DC = docker compose

lint:
	$(DC) exec php composer lint:phpstan
	$(DC) exec php composer lint:fixer:check

lint-fix:
	$(DC) exec php composer lint:fixer

test:
	$(DC) exec php bin/phpunit

reset-test-db:
	$(DC) exec php bin/console doctrine:database:drop -f --env=test
	$(DC) exec php bin/console doctrine:database:create --env=test
	$(DC) exec php bin/console doctrine:migrations:migrate --allow-no-migration --no-interaction --env=test
	$(DC) exec php bin/console doctrine:fixtures:load --no-interaction --env=test
