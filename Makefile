CONTAINER_NAME=tenx-app-1

coverage-html:
	docker exec $(CONTAINER_NAME) /bin/sh -c 'XDEBUG_MODE=coverage php -d memory_limit=512M vendor/bin/phpunit  --coverage-html cover/'

docker-build:
	docker compose build

docker-up:
	docker compose up

docker-down:
	docker compose down

docker-test:
	docker exec $(CONTAINER_NAME) composer test
