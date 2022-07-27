# Contriubting to vultr-php

## Overview

- All code should run through `composer lint`
- All code must **must be tested** `composer test`

## Linting

We try to maintain a blend of some PSR-2 Coding standards and some others so it's always advised to run the linter.
This ensures that the code that is being maintained is standardized between the project.

To run the linter:

```sh
composer lint
```

## Testing

We try to have as much code coverage as possible see our current code [coverage](https://vultr.github.io/vultr-php/code-coverage/index.html)

To run the tests locally. Change directory into the vultr-php project folder.

To run tests locally:

```sh
composer test
```

To run a specific test:

```sh
composer test -- --filter AccountTest
```

## Versioning

Vultr Php follows [SemVer](http://semver.org/) for versioning. New functionality will result in a increment to the MINOR version and bug fixes will result in a increment to the PATCH version. Breaking changes or incompatible Client changes will result in MAJOR version.

## Releases

Releases of new versions are done as independent pull requests and will be made by the maintainers.

To release a new version of `vultr-php` we must do the following:

- Update version number in `src/VultrConfig.php` to reflect the new release version
- Make the appropriate updates to `CHANGELOG.md`. This should include the:
  - Version,
  - List of fix/features with accompanying pull request ID
  - Description of each fix/feature

```
## v0.0.1 (2022-05-05)

### Fixes
* Fixed random bug #12

### Features
* BareMetalServer functionality #13
```

- Submit a pull request with the changes above.
- Once the pull request is merged in, create a new tag and publish.