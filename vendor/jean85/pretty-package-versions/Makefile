pre-commit-check: composer cs phpstan psalm test

composer:
	composer install

cs:
	vendor/bin/php-cs-fixer fix --verbose

cs-dry-run:
	vendor/bin/php-cs-fixer fix --verbose --dry-run

phpstan:
	vendor/bin/phpstan analyze

psalm:
	vendor/bin/psalm

test:
	vendor/bin/phpunit
