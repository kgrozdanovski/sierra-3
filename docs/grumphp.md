# GrumPHP Configuration and Caveats

GrumPHP is a PHP code quality tool that can be used to enforce coding standards and best practices. 
It is a great tool to use in a CI/CD pipeline to ensure that code is of a high quality before it is merged into the
main branch.

Whenever you change this configuration make sure you apply the changes to the Git hooks by running the following
command from your host machine:

```bash
docker compose exec test /var/www/html/api/vendor/bin/grumphp git:init --config='/var/www/html/api/config/grumphp.yml'
```

> Note: Make sure you have the `test` service running on your host.

The actual output from the Grum pre-commit hook can be viewed in the Git->Console window from PHPStorm if using the
IDE GUI interface or in the terminal if using the command line.

## Configuration

### Environment Variables 

GrumPHP is configured using a `grumphp.yml` file. This file is located in the `api/config` directory (relative to the
project root).

To be able to use GrumPHP in our custom-structured project and within a Dockerized environment several changes have
been made to the default configuration, among which the following environment variables:

```bash
GRUMPHP_PROJECT_DIR: '/var/www/html'
GRUMPHP_COMPOSER_DIR: '/var/www/html/api'
GRUMPHP_BIN_DIR: '/var/www/html/api/vendor/bin'
```

The values of these variables are paths from within the Docker container to which we attach every time the
pre-commit hook is called (i.e. when a Git commit is made). The directory structure within the container is
identical to the one used by the `api` service.

More details related to these variables may be found in the official GrumPHP documentation on
[Exotic Installation](https://github.com/phpro/grumphp/blob/v2.x/doc/installation/exotic.md).

### Tasks and Commands

GrumPHP offers a broad range of pre-made tasks that can be run on the codebase. These tasks may be defined and
configured to run in the `grumphp.yml` file `tasks` section.

Official documentation on how to use commands available within GrumPHP may be found in the
[Commands Documentation](https://github.com/phpro/grumphp/blob/v2.x/doc/commands.md).
A complete list of available tasks and their configurable properties may be found in the official GrumPHP documentation
on [Tasks](https://github.com/phpro/grumphp/blob/v2.x/doc/tasks.md).


## Caveats

### Regarding `EXEC_GRUMPHP_COMMAND` and docker bind paths

This is the command that will be executed in the `.git/hooks/pre-commit` shell script when triggered with a Git commit.

We override the default exec command because this project is designed to be entirely run within a Docker container
and without any external dependencies installed on the host machine. Note that this command is run from within a
Git Bash shell on the host.

> If a config path is provided in `composer.json::extra::grumphp::config-default-path`, it will be added to this
> command as the `--config` CLI argument. More in [Configuration in `composer.json`](#configuration-in-composerjson).

There is a known issue with Git Bash and binding Windows paths to the Docker container. This is further complicated
if our project is placed within a WSL (Windows Subsystem for Linux) environment, meaning the path relative from the
host is a network drive (e.g. `\\wsl$\Ubuntu-20.04\home\user\project`).

From a [StackOverflow post](https://stackoverflow.com/a/50608818/4111323):

> So with some extra digging I found these three threads, related to git-bash mucking up docker mount:
> 
> https://forums.docker.com/t/weird-error-under-git-bash-msys-solved/9210 https://github.com/moby/moby/issues/24029#issuecomment-250412919
> 
> When I look up mingw's documentation on the path conversion git-bash is using, I find this table of syntax: http://www.mingw.org/wiki/Posix_path_conversion
> 
> One of which outputs in the format: x;x;C:\MinGW\msys\1.0\x. Note the ;C in it. If git-bash is trying to be clever, stuffing up the syntax and outputting a path with this format, this would explain it.
> 
> Solution is to escape the path conversion, using by prefixing with /. So the working docker command to run docker from git-bash with present working directory:
> 
> docker run -it --rm -v /${PWD}:/wkDir $IMAGE_TAG yarn dev

One way to work around this issue is to set the `MSYS_NO_PATHCONV=1` environment variable before running commands
from Git Bash.

Another workaround (which is used in this project) is to avoid defining mounts and simply attach to an existing service.

### Configuration in `composer.json`

The `composer.json` file contains a `grumphp` subsection in the `extra` section. The only property we need to modify
here is `config-default-path`. This property is used to specify the path to the `grumphp.yml` file relative to the
`composer.json` configuration itself. This variable is later appended to the `EXEC_GRUMPHP_COMMAND` as the `--config`
CLI argument value.

```json
"extra": {
    "grumphp": {
        "config-default-path": "config/grumphp.yml"
    }
}
```

We also need to specify it when updating the Git hooks with the command outlined in the first section. In this case,
the path is relative to the project root from within the Docker container.