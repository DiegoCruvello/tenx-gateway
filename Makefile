CONTAINER_NAME=tenx-app-1

coverage-html:
	docker exec $(CONTAINER_NAME) /bin/sh -c 'XDEBUG_MODE=coverage php -d memory_limit=512M vendor/bin/phpunit  --coverage-html cover/'
