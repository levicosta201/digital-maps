.DEFAULT_GOAL := help

build: ## build develoment environment with laradock
	if ! [ -f .env ];then cp .env.example .env;fi
	docker-compose up -d --build
	docker-compose run --rm laravel.digimaps composer install
	docker-compose run --rm laravel.digimaps php artisan key:generate

up: ## start development environment
	docker-compose up -d

down: ## stop development environment
	docker-compose down

migrate: ## migrate database
	docker-compose run --rm laravel.digimaps php artisan migrate
