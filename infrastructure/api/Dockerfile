FROM php:8.3-fpm

# Declare build args
ARG app_env

# get Composer phar
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Run apt-get in quiet mode and assume yes on prompts
RUN apt-get update -qy

# Install dependencies for php-gd extension
RUN apt-get install -qy libpng-dev libjpeg-dev gettext libzip-dev zip libpq-dev libsodium-dev libicu-dev

# Install PHP extensions
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure intl \
    && docker-php-ext-install gd zip bcmath gettext pdo pdo_pgsql pgsql intl

# Install PECL extensions
RUN pecl install excimer && docker-php-ext-enable excimer # Needed by Sentry.io for performance monitoring

# Allow Composer to be run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Copy Composer manifests
COPY ./api/composer.json ./api/composer.lock ./api/symfony.lock ./

COPY ./api/bin ./bin

# Install Composer dependencies with dev dependencies on 'dev' environment
RUN if [ "x$app_env" = "xdev" ]; \
then composer install --no-interaction --no-plugins --no-scripts; \
else composer install --no-interaction --no-plugins --no-scripts --no-dev;\
fi

# Optimize the Composer Autoloader
RUN composer dump-autoload --optimize

# Use custom php.ini
COPY ./infrastructure/api/environment/${app_env}/php.ini /usr/local/etc/php/php.ini

# Xdebug support (enable only for dev env)
# Make sure to install a specific Xdebug version compatible with the PHP version (https://xdebug.org/docs/compat)
# Warning: Xdebug is known to slow down the application significantly and will also disable PHP-JIT (PHP 8.0+)
RUN if [ "x$app_env" = "xdev" ]; then pecl install xdebug-3.3.1 && docker-php-ext-enable xdebug; fi