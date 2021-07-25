# code-snippets

<p align="center">
    <img src="https://img.shields.io/github/v/release/permafrost-dev/code-snippets.svg?sort=semver&logo=github" alt="Package Version">
    <img src="https://img.shields.io/github/license/permafrost-dev/code-snippets.svg?logo=opensourceinitiative&" alt="license">
    <img src="https://github.com/permafrost-dev/code-snippets/actions/workflows/run-tests.yml/badge.svg?branch=main&" alt="Test Run Status">
    <img src="https://codecov.io/gh/permafrost-dev/code-snippets/branch/main/graph/badge.svg" alt="code coverage">
</p>

Easily create and work with code snippets from source code files of any type in PHP.

_The original code this package is based on was borrowed from the [`spatie/backtrace`](https://github.com/spatie/backtrace) package._

## Installation

You can install the package via composer:

```bash
composer require permafrost-dev/code-snippets
```

## Usage

_Note: Although the examples here reference php files, any file type can be used when creating a `CodeSnippet`._

### Creating a snippet

Use the `surroundingLine($num)` method to select the "target" line, which will be returned as the middle line of the snippet:

```php
use Permafrost\CodeSnippets\CodeSnippet;

$snippet = (new CodeSnippet())
    ->surroundingLine(4)
    ->snippetLineCount(6)
    ->fromFile('/path/to/a/file.php');
```

Use the `surroundingLines($first, $last)` method to select a range of "target" lines, which will be returned as the middle lines of the snippet:

```php
use Permafrost\CodeSnippets\CodeSnippet;

$snippet = (new CodeSnippet())
    ->surroundingLines(4, 7)
    ->snippetLineCount(6)
    ->fromFile('/path/to/a/file.php');
```

Use the `linesBefore()` and `linesAfter()` methods to specify the number of context lines to display before and after the "target" lines:

```php
use Permafrost\CodeSnippets\CodeSnippet;

// the "target" line isn't displayed in the middle, but as the second line
$snippet = (new CodeSnippet())
    ->surroundingLine(4)
    ->linesBefore(1)
    ->linesAfter(3)
    ->fromFile('/path/to/a/file.php');
```
### Getting the snippet contents

The `getLines()` method returns an array of `SnippetLine` instances.  The keys of the resulting array are the line numbers.

The `SnippetLine` instances may be cast to strings to display the value.

```php
use Permafrost\CodeSnippets\CodeSnippet;

// the "target" line isn't displayed in the middle, but as the second line
$snippet = (new CodeSnippet())
    ->surroundingLine(4)
    ->snippetLineCount(5)
    ->fromFile('/path/to/a/file.php');
    
foreach($snippet->getLines() as $lineNum => $line) {
    // use ->isSelected() to determine if the line was selected using the
    // surroundingLine() or surroundingLines() method
    $prefix = $line->isSelected() ? ' * ' : '   ';
    
    echo "{$prefix}{$line->lineNumber()} - {$line}" . PHP_EOL;
    // or
    echo $prefix . $line->lineNumber() . ' - ' . $line->value() . PHP_EOL;
}
```

### Snippet line count

To determine the number of lines in the snippet, use the `getSnippetLineCount()` method:

```php
$snippet = (new CodeSnippet())
    ->surroundingLines(4, 7)
    ->linesBefore(3)
    ->linesAfter(3)
    ->fromFile('/path/to/a/file.php');
    
echo "Snippet line count: " . $snippet->getSnippetLineCount() . PHP_EOL;
```

You can also use `count()` on the result of the `getLines()` method:

```php
$snippet = (new CodeSnippet())
    ->surroundingLines(4, 7)
    ->linesBefore(3)
    ->linesAfter(3)
    ->fromFile('/path/to/a/file.php');
    
echo "Snippet line count: " . count($snippet->getLines()) . PHP_EOL;
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
