FROM php:8.3-fpm

# Zaruriy tizim kutubxonalarini o‘rnatish
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    libicu-dev \
    git curl sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite intl zip

# Composer o‘rnatish
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Ishchi katalog
WORKDIR /var/www
