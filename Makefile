install:
	docker compose exec symfony composer install
	docker compose exec symfony php bin/console doctrine:migrations:migrate --no-interaction
	docker compose exec symfony php bin/console app:seed