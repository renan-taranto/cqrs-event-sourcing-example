DOCKER_COMPOSE = docker-compose
EXEC_PHP = $(DOCKER_COMPOSE) exec php
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
test: ## runs tests
	$(EXEC_PHP) bin/phpunit

test-coverage: ## runs tests and creates a code coverage report
	$(EXEC_PHP) bin/phpunit --coverage-html $(COVERAGE_PATH) --stop-on-failure

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
