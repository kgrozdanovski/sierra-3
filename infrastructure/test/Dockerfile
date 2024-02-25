FROM php:8.3-fpm

# get Composer phar
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Run apt-get in quiet mode and assume yes on prompts
RUN apt-get update -qy

# Install dependencies for php-gd extension
RUN apt-get install -qy git libpng-dev libjpeg-dev gettext libzip-dev zip libpq-dev libsodium-dev libicu-dev

# Install PHP extensions
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure intl \
    && docker-php-ext-install gd zip bcmath gettext pdo pdo_pgsql pgsql intl

# Allow Composer to be run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Create a ./api directory
RUN mkdir -p /var/www/html/api

WORKDIR /var/www/html/api

# Copy Composer manifests
COPY ./api/composer.json ./api/composer.lock ./api/symfony.lock ./

COPY ./api/bin ./bin

# Install Composer dependencies with dev dependencies
RUN composer install --no-interaction --no-plugins --no-scripts;

# Optimize the Composer Autoloader
RUN composer dump-autoload --optimize

# Use custom php.ini
COPY ./infrastructure/api/environment/dev/php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www/html