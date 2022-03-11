# ####################### #
# ##### Application ##### #
# ####################### #
PROJECT_NAME=helloworld-api

app.init: docker.up app.composer.install install-dev db.dev

dev:
	yarn encore dev --watch

app.permissions:
	docker-compose -p $(PROJECT_NAME) exec -uroot php chown -R www-data:www-data /app/var || true

app.cc: app.permissions
	docker-compose -p $(PROJECT_NAME) exec php bin/console c:c --no-warmup
	docker-compose -p $(PROJECT_NAME) exec php bin/console c:w

app.migration.diff:
	docker-compose -p $(PROJECT_NAME) exec php /app/bin/console doctrine:migration:diff

app.migration.generate:
	docker-compose -p $(PROJECT_NAME) exec php /app/bin/console doctrine:migration:generate

app.migration.migrate:
	docker-compose -p $(PROJECT_NAME) exec php /app/bin/console doctrine:migration:migrate

app.composer.install: app.permissions
	docker-compose -p $(PROJECT_NAME) exec php composer install

db.dev:
	docker-compose -p $(PROJECT_NAME) exec php php bin/console --no-debug d:d:d --force --if-exists
	docker-compose -p $(PROJECT_NAME) exec php php bin/console --no-debug d:d:c
	docker-compose -p $(PROJECT_NAME) exec php php bin/console d:m:m --no-interaction
	docker-compose -p $(PROJECT_NAME) exec php php bin/console doctrine:fixtures:load -n

# PROD DON'T TOUCH THIS
install:
	yarn install
	composer install --no-dev --optimize-autoloader
	APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
	php bin/console doctrine:migration:migrate --no-interaction

install-dev:
	yarn install
	APP_ENV=dev APP_DEBUG=1 php bin/console cache:clear

# ################## #
# ##### Docker ##### #
# ################## #

docker.up:
	rm -rf var/{cache,log,sessions}
	@$(call echoc,Start container)
	docker-compose -p $(PROJECT_NAME) up -d --build;\

docker.connect:
	docker-compose -p $(PROJECT_NAME) exec php bash

docker.down:
	docker-compose -p $(PROJECT_NAME) down

docker.reset:
	docker-compose -p $(PROJECT_NAME) down -v --remove-orphans

docker.run:
	docker-compose -p $(PROJECT_NAME) exec php $(command)

# ########################### #
# ##### Coding Standard ##### #
# ########################### #

cs.fixer:
	vendor/bin/php-cs-fixer fix
