#!make

start: ## start local env
	docker-compose up -d --build
	make composer-install
	make phinx-migrate

up: ## docker-compose up
	docker-compose up -d

down: ## docker-compose down
	docker-compose down

bash: ## bash into the app container
	docker exec -it pickupPoints.php bash

composer-install: ## composer install
	docker exec -it pickupPoints.php composer install

phinx-migrate: ## migrate database
	docker exec -it pickupPoints.php bin/console.php phinx:migrate

import: ## import pickup points
	docker exec -it pickupPoints.php bin/console.php import:start

phpcs: ## run phpcs
	docker exec -it pickupPoints.php composer run phpcs

phpcbf: ## run phpcbf
	docker exec -it pickupPoints.php composer run phpcbf

test: ## run tests
	docker exec -it pickupPoints.php composer run test

.PHONY: help
help: ## show this help
	@grep -h -E '^##.*$$' $(MAKEFILE_LIST) | sed -r 's/^## ?//'
	@grep -h -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'
