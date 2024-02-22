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

## Performance Improvements

### Docker on Windows Host

Running Symfony applications on Docker on a Windows host can be slow due to the file system performance.

You can improve the performance of the application by using WSL2 as the backend for Docker.
You can do this by following the instructions in the 
[Docker documentation](https://docs.docker.com/docker-for-windows/wsl/).

Furthermore, and more importantly, you should move your project files inside the WSL2 file system. This can be done 
from within the WSL2 terminal by running the following commands:
```bash
mkdir sierra-3
cp -r "/mnt/c/<path-on-host>/sierra-3" .
```

In the PHPStorm you may now load the project from the WSL2 file system by using the following path:
```text
\\wsl$\<distro>\home\<username>\sierra-3
```

You could possibly see a 100-fold improvement in the performance of the application. This is now much closer to the
expected performance of the application on an environment with a Linux host.

### Xdebug

Xdebug is a powerful tool for debugging PHP applications. However, it can slow down the application significantly.

To turn off Xdebug, you can comment out the entire contents of the `xdebug.ini` file in the 
`infrastructure/api/environment/dev` directory and restart the API container.

### Environment Variables

Disabling the `APP_DEBUG` environment variable in your `.env` file can also improve the performance of the application,
in cases even doubling it. This variable affects the functionality of the Symfony framework and should always be turned
off for non-dev environments.
