DOCKER_COMPOSE = docker-compose
EXEC_PHP = $(DOCKER_COMPOSE) exec php
EXEC_MYSQL = $(DOCKER_COMPOSE) exec mysql
COMPOSER = $(EXEC_PHP) composer
SYMFONY = $(EXEC_PHP) bin/console
COVERAGE_PATH = .docker/php/code-coverage

##
##Docker
start: ## starts the application
	$(DOCKER_COMPOSE) up -d

logs: ## shows the application logs
	$(DOCKER_COMPOSE) logs -f

##
##Composer
vendor: composer.json composer.lock ## installs composer dependencies
	$(COMPOSER) install
require: ## install a new composer dependency
	$(COMPOSER) require $(pkg)

##
##Symfony
clear-cache: ENV=dev
clear-cache: ## clears the Symfony cache
	$(SYMFONY) cache:clear --env=$(ENV)

##
##Event Stream
event-stream: ## creates the event stream
	$(SYMFONY) event-stream:create

##
##Testing
create-test-db: ## creates the testing database
	$(EXEC_MYSQL) mysql -u root -p12345678 -e 'create database `appdb-test`; GRANT ALL PRIVILEGES ON `appdb-test`.* TO `dbuser`@`%`'

test-unit: ## runs unit tests
	$(EXEC_PHP) php vendor/bin/codecept run unit

test-integration: ## runs integration tests
	$(EXEC_PHP) php vendor/bin/codecept run integration

test-functional: ## runs functional tests
	$(EXEC_PHP) php vendor/bin/codecept run functional

test-api: ## runs api tests
	$(EXEC_PHP) php vendor/bin/codecept run api

test-all: ## runs all tests
	$(EXEC_PHP) php vendor/bin/codecept run unit,integration,functional,api

test-coverage: ## runs all tests and creates a code coverage report
	$(EXEC_PHP) php vendor/bin/codecept run unit,integration,functional,api --coverage-html

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
