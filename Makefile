.PHONY : i t it

COMPOSER = $(shell which composer)
PHPUNIT = vendor/bin/phpunit
PHPCS = vendor/bin/php-cs-fixer
PHP = $(shell which php)

it: i t

i:
	$(COMPOSER) install

t:
	$(PHPUNIT)
	$(PHPCS) fix -v
	$(PHP) vendor/bin/phpstan analyze -l 4 src tests
#	$(PHP) vendor/bin/phpstan analyze -l 1 -c phpstan.neon tests
#	bin/phpda analyze src
