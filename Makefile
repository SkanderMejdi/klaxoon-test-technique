ID_USER = $(shell id -u)
ID_GROUP = $(shell id -g)
TARGET_TEST := klaxoon-api:dev
PHP_EXEC := docker-compose exec -T --user $(ID_USER):$(ID_GROUP) php
 
build:
	docker build -t $(TARGET_TEST) --target dev .

init:
	cp .env.dist .env

start:
	ID_USER=$(ID_USER) ID_GROUP=$(ID_GROUP) docker-compose up -d 

composer:
	$(PHP_EXEC) composer install
 
database:
	$(PHP_EXEC) bin/console doctrine:schema:update

stop:
	ID_USER=$(ID_USER) ID_GROUP=$(ID_GROUP) docker-compose down --volumes

test:
	docker-compose -f docker-compose.test.yml run php vendor/bin/behat -vvv

integration-test:
	docker-compose -f docker-compose.integration.yml run php vendor/bin/behat -vvv
