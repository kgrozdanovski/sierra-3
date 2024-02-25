# Contributing

The following is a guide towards contributing to the Sierra-3 project.
This document describes the typical workflow, the coding standards, branching strategy
and the commit message conventions.

## Coding Standards

The Coding Standard enforced in this project follows the [PSR-12](https://www.php-fig.org/psr/psr-12/) and
[PHP-PER]() specifications. The code is automatically linted by the CI/CD pipeline and the build will fail if the
standard is breached by new code.

## Commit Messages

The commit messages should follow the [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/)
specification. This enables us to automate the versioning and changelog generation process. If the commit
message does not follow the specification, the CI/CD pipeline will fail the build.

> When committing changes, due to the nature of the Git hooks configured for this project, you must have the Docker
> engine running on your host machine. This is because the Git hooks are executed from within a Docker container.

## Merge Request Process

1. Before creating a merge request, make sure that your branch is up-to-date with the `development` branch.
   This can be done by switching to the `development` branch and pulling the latest changes from the remote
   repository. Then switch back to your branch and rebase your branch onto the `development` branch.
2. Ensure that all existing tests pass successfully, including Code Quality and Unit tests.
3. Make sure that the code is properly documented and that the documentation is up-to-date.
4. You may merge the Pull Request in once you have a code review from at least one other developer,
   or if you do not have permission to do that, you may request the second reviewer to merge it for you.

## Versioning

The versioning scheme we use is [SemVer](http://semver.org/) and the format is as follows:
`<major>.<minor>.<patch>`.
The version is automatically bumped by the CI/CD pipeline when a new tag is pushed to the repository.
