.DEFAULT_GOAL := help

build: ## build develoment environment with laradock
	if ! [ -f .env ];then cp .env.example .env;fi
	docker-compose build
	docker-compose run --rm laravel.digimaps composer install
	docker-compose run --rm laravel.digimaps php artisan key:generate
	docker-compose run --rm laravel.digimaps php artisan migrate
