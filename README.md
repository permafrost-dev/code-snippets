# Easily work with snippets of code

<!--
[![Latest Version on Packagist](https://img.shields.io/packagist/v/permafrost-dev/code-snippets.svg?style=flat-square)](https://packagist.org/packages/permafrost-dev/code-snippets)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/permafrost-dev/code-snippets/run-tests?label=tests)](https://github.com/permafrost-dev/code-snippets/actions?query=workflow%3ATests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/permafrost-dev/code-snippets/Check%20&%20fix%20styling?label=code%20style)](https://github.com/permafrost-dev/code-snippets/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/permafrost-dev/code-snippets.svg?style=flat-square)](https://packagist.org/packages/permafrost-dev/code-snippets)
-->

<p align="center">
    <img src="https://img.shields.io/github/v/release/permafrost-dev/code-snippets.svg?sort=semver&logo=github" alt="Package Version">
    <img src="https://img.shields.io/github/license/permafrost-dev/code-snippets.svg?logo=opensourceinitiative" alt="license">
    <img src="https://github.com/permafrost-dev/code-snippets/actions/workflows/run-tests.yml/badge.svg?branch=main" alt="Test Run Status">
    <img src="https://codecov.io/gh/permafrost-dev/code-snippets/branch/main/graph/badge.svg?token=jdCDagIVFK" alt="code coverage">
</p>

Easily work with snippets of code from source code files.

## Installation

You can install the package via composer:

```bash
composer require permafrost-dev/code-snippets
```

## Usage

```php
use Permafrost\CodeSnippets\CodeSnippet;

$snippet = (new CodeSnippet())
    ->surroundingLine(4)
    ->snippetLineCount(6)
    ->fromFile('/path/to/a/file.php);
```

## Testing

```bash
./vendor/bin/phpunit
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Patrick Organ](https://github.com/patinthehat)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
