# Yard Skeleton Package

[![Test Coverage](https://raw.githubusercontent.com/Hi-Folks/array/main/badge-coverage.svg)](https://packagist.org/packages/hi-folks/array)

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
