COMPOSER ?= composer

install:
	$(COMPOSER) install
	npm install
	cp .env.example .env
	php artisan key:generate
	php artisan migrate --seed
	npm run build

test:
	php artisan test

quality:
	vendor/bin/pint --test
	vendor/bin/phpstan analyse --memory-limit=1G

fix:
	vendor/bin/pint

serve:
	php artisan serve
