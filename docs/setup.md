# Initial Project Setup & Deployment

## Prerequisites

You need to have Docker Engine installed on your local machine. If you are using Windows, make sure you disable
Hyper-V and enable WSL2 through Windows Features before installing Docker.

## Getting Started

1. Clone the repository on your local machine
    ```shell
    SSH clone URL: ssh://git@git.jetbrains.space/involve/diamond-data-provider/diamond-data-provider.git
    HTTPS clone URL: https://git.jetbrains.space/involve/diamond-data-provider/diamond-data-provider.git
    ```

2. Install the Composer dependencies by running
    ```shell
    docker run --rm --interactive --tty --volume .:/app composer install
    ```
    > Note: This is only necessary on a dev environment or if you are not using Docker to run the application.

3. Build the application images by running
    ```shell
    docker build --build-arg app_env=dev -t sierra/api -f ./infrastructure/api/Dockerfile .
   ```

4. Run the entire container constellation by running
   ```shell
   APP_ENV=dev docker-compose up -d
    ```
   or on Windows
   ```shell
   $env:APP_ENV="dev"; docker-compose up -d
   ```
   
5. Run the database migrations by running
   ```shell
   docker-compose exec api php bin/console doctrine:migrations:migrate
    ```

5Add the following entries to your `/etc/hosts` or `C:\Windows\System32\drivers\etc\hosts` file
    ```text
    127.0.0.1 api.sierra.local
    127.0.0.1 db.sierra.local
    ```
   > Note: If you are using Windows, you need to run your editor as administrator in order to be able
   > to edit the hosts file.

### Optional cron runner

You can choose to set up a local cron container which will periodically run the cron jobs for you.
To do so, make sure you have already built the `sierra/api` image.

Then you can run the following sequence of commands:

1. Build the cron image by running
   ```shell
   docker build --build-arg app_env=dev -t sierra/cron -f ./infrastructure/cron/Dockerfile .
   ```
2. Run the cron container by running
   ```shell
   APP_ENV=dev docker-compose up -d cron
    ```
   or on Windows
   ```shell
   $env:APP_ENV="dev"; docker-compose up -d cron
   ```

### Running Command classes manually

You can run a command classes manually by running the following command from the host machine:
 ```shell
 docker-compose exec api php bin/console <commandName>
 ```

IDEs such as PHPStorm can also be used to run the command classes manually in a more convenient way.
An example configuration for PHPStorm can be found in the `.run` directory.
