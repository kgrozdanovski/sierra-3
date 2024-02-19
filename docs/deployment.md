## Deployment

New versions are automatically deployed to the production environment via GitHub Actions
every time a tag in the format `release/*` is pushed to the repository.

Database migrations are automatically run on the production environment and the entire deployment is
reverted in case of a failure.

> If there are any changes to non-versioned files such as .env or other configuration files, you will
have to update them manually on the production environment or a secrets vault.

### Manual deployment

If you choose to perform a manual deployment, you can do so by running the same steps
as for the local setup, but with the following changes:
- Stop the running `nginx` and `cron` containers by running `docker-compose down nginx cron`;
- Pull the latest changes from the repository;
- Run the database migrations by running `docker-compose exec api bin/console doctrine:migrations:migrate`;
- When building the Docker images, make sure you change the `--build-arg app_env=dev` to `--build-arg app_env=prod`
  for all the images;
- After pulling the latest changes from the repository, make sure you remove the `docker-compose.override.yml` file
  or specify the exact configuration file, i.e. `APP_ENV=prod docker compose -f docker-compose.yml up -d`
- Refresh the cache by running `docker-compose exec api bin/console cache:clear`;
- Re-optimize the autoloader by running 
`docker-compose exec api composer dump-autoload --no-dev --classmap-authoritative`;

> Note: Manually updating PHP files on the production environment is not recommended. Apart from the usual reasons,
> it might also not work as expected due to the opcache setting `opcache.validate_timestamps=0` in the `php.ini` file.