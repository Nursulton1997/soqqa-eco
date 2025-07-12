FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    libicu-dev \
    git curl sqlite3 libsqlite3-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite intl zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
