.DEFAULT_GOAL := help

build: ## build develoment environment with laradock
	if ! [ -f .env ];then cp .env.example .env;fi
	docker-compose run --rm laravel.digimaps composer install
	./vendor/bin/sail up -d
	docker-compose run --rm laravel.digimaps php artisan key:generate

up: ## start development environment
	docker-compose up -d

down: ## stop development environment
	docker-compose down

test: ## run test
	docker-compose run --rm laravel.digimaps php artisan test

migrate: ## migrate database
	docker-compose run --rm laravel.digimaps php artisan migrate

seed: ## seed database
	docker-compose run --rm laravel.digimaps php artisan db:seed
