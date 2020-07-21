DOCKER_COMPOSE = docker-compose
EXEC_WEB_API = $(DOCKER_COMPOSE) exec web_api
EXEC_MYSQL = $(DOCKER_COMPOSE) exec mysql
EXEC_MONGO = $(DOCKER_COMPOSE) exec mongo
COMPOSER = $(EXEC_WEB_API) composer
SYMFONY = $(EXEC_WEB_API) bin/console
COVERAGE_PATH = .docker/php/code-coverage

.PHONY: install start logs vendor require clear-cache test-unit test-integration test-functional test-api test-all test-coverage load-mongo-fixtures

##
##Setup
build:
	$(DOCKER_COMPOSE) build --build-arg uid=$$(id -u) --build-arg gid=$$(id -g)

install: ## builds, starts the application and installs dependencies
install: build start vendor

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
##Testing
test-unit: ## runs unit tests
	$(EXEC_WEB_API) php vendor/bin/codecept run unit

test-integration: ## runs integration tests
	$(EXEC_WEB_API) php vendor/bin/codecept run integration

test-functional: ## runs functional tests
	$(EXEC_WEB_API) php vendor/bin/codecept run functional

test-api: ## runs api tests
	$(EXEC_WEB_API) php vendor/bin/codecept run api

test-all: ## runs all tests
	$(EXEC_WEB_API) php vendor/bin/codecept run unit,integration,functional,api

test-coverage: ## runs all tests and creates a code coverage report
	$(EXEC_WEB_API) php vendor/bin/codecept run unit,integration,functional,api --coverage-html

test-clover: ## runs all tests and creates a clover xml report
	$(EXEC_WEB_API) php vendor/bin/codecept run unit,integration,functional,api --coverage-xml build/logs/clover.xml

load-mongo-fixtures: ## transforms the json files located at /tests/etc/_data/fixtures into dump files to be used while testing
	$(EXEC_MONGO) sh load-fixtures.sh

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
