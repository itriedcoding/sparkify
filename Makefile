SHELL := /bin/sh

.PHONY: up down logs web sparkify phpunit build

up:
	docker compose up --build

down:
	docker compose down

logs:
	docker compose logs -f --tail=200

web:
	cd web && npm run dev

sparkify:
	cd sparkify && php -S 0.0.0.0:8000 -t public public/index.php

phpunit:
	cd sparkify && ./vendor/bin/phpunit --colors=always

build:
	cd web && npm run build