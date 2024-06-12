# Yard Skeleton Package

![PHPStan Badge](https://github.com/yardinternet/skeleton-package/actions/workflows/phpstan.yml/badge.svg)
![Tests Badge](https://github.com/yardinternet/skeleton-package/actions/workflows/run-tests.yml/badge.svg)
![Code Coverage Badge](https://raw.githubusercontent.com/yardinternet/skeleton-package/badges/coverage.svg?token=GHSAT0AAAAAACCCLHCHAKVMDIPFZCEM5M22ZTJQTRQ)
![Lines of Code Badge](https://raw.githubusercontent.com/yardinternet/skeleton-package/badges/lines-of-code.svg?token=GHSAT0AAAAAACCCLHCHI4HRH4FZTJ7MQBOAZTJQUMQ)

This repository provides a scaffold for creating an Acorn package. For more detailed information, please refer to the [Acorn Package Development](https://roots.io/acorn/docs/package-development/) documentation.

## Installation

To install this package using Composer, follow these steps:

1. Add the following to the `repositories` section of your `composer.json`:

    ```json
    {
      "type": "vcs",
      "url": "git@github.com:yardinternet/skeleton-package.git"
    }
    ```

2. Add the following to the `require` section of your `composer.json`:

    ```json
    {
      "yard/skeleton-package": "*"
    }
    ```

3. Run the following command to install the package:

    ```sh
    composer update
    ```

You can publish the config file with:

```shell
wp acorn vendor:publish --provider="Yard\SkeletonPackage\ExampleServiceProvider"
```

## Usage

From a Blade template:

```blade
@include('Example::example')
```

From WP-CLI:

```shell
wp acorn example
```
