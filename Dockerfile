FROM php:8.4-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip sqlite3 libsqlite3-dev libzip-dev libicu-dev \
    && docker-php-ext-install intl pdo_mysql pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
