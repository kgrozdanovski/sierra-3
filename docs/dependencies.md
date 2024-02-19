# Managing PHP dependencies

## System

When there is a need to add, update or remove system libraries, we must alter the relevant
Dockerfile, rebuild the image for the appropriate environment and restart the container.

PHP extensions should be managed with the `docker-php-ext-*` scripts from within the Dockerfile.

Keep in mind that PECL is also available within the Docker `php-fpm` image.

## Composer

To install new Composer libraries, you can use the following command:

```shell
docker run --rm --interactive --tty --volume .:/app composer require <package-name>
```

To update existing Composer libraries, you can use the following command:

```shell
docker run --rm --interactive --tty --volume .:/app composer update
```

### Symfony

When updating Symfony packages, especially when upgrading a major version, you should
follow the official guide [here](https://symfony.com/doc/current/setup/upgrade_major.html).