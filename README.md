# Laravel Filter Var

[![Latest Stable Version](https://img.shields.io/packagist/v/aporat/laravel-filter-var.svg?style=flat-square&logo=composer)](https://packagist.org/packages/aporat/laravel-filter-var)
[![Monthly Downloads](https://img.shields.io/packagist/dm/aporat/laravel-filter-var.svg?style=flat-square&logo=composer)](https://packagist.org/packages/aporat/laravel-filter-var)
[![Codecov](https://img.shields.io/codecov/c/github/aporat/laravel-filter-var?style=flat-square)](https://codecov.io/github/aporat/laravel-filter-var)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-orange.svg?style=flat-square)](https://laravel.com/docs/12.x)
![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/aporat/laravel-filter-var/ci.yml?style=flat-square)
[![License](https://img.shields.io/packagist/l/aporat/laravel-filter-var.svg?style=flat-square)](https://github.com/aporat/laravel-filter-var/blob/master/LICENSE)

A Laravel package for filtering and sanitizing request variables with a chainable, customizable filter system.

## Features
- Chain multiple filters (e.g., `trim`, `uppercase`, `cast`) in a single call.
- Built-in filters for common tasks (e.g., `strip_tags`, `escape`, `format_date`).
- Support for custom filters via configuration.
- Seamless integration with Laravel's service container and facade system.

## Requirements
- **PHP**: 8.3 or higher
- **Laravel**: 10.x, 11.x or 12.x
- **Composer**: Required for installation

## Installation
Install the package via [Composer](https://getcomposer.org/):

```bash
composer require aporat/laravel-filter-var
```

The service provider (`FilterVarServiceProvider`) is automatically registered via Laravel’s package discovery. If you’ve disabled auto-discovery, add it manually to `config/app.php`:

```php
'providers' => [
    // ...
    Aporat\FilterVar\Laravel\FilterVarServiceProvider::class,
],
```

Optionally, register the facade for cleaner syntax:

```php
'aliases' => [
    // ...
    'FilterVar' => Aporat\FilterVar\Laravel\Facades\FilterVar::class,
],
```

Publish the configuration file to customize filters:

```bash
php artisan vendor:publish --provider="Aporat\FilterVar\Laravel\FilterVarServiceProvider" --tag="config"
```

This copies `config/filter-var.php` to your Laravel config directory.

## Usage

### Basic Filtering
Filter and sanitize a request variable using the facade:

```php
use Aporat\FilterVar\Laravel\Facades\FilterVar;

$userAgent = FilterVar::filterValue('cast:string|trim|strip_tags|escape', $request->header('User-Agent'));
```

This:
- Casts the input to a string.
- Trims whitespace.
- Removes HTML/PHP tags.
- Escapes special HTML characters.

### Available Filters

| Filter                 | Description                                        | Example Input             | Example Output         |
|------------------------|----------------------------------------------------|---------------------------|------------------------|
| `capitalize`           | Capitalizes words (title case)                    | `hello world`             | `Hello World`          |
| `cast:<type>`          | Casts to a type (e.g., `int`, `string`, `bool`)   | `123.45` (cast:int)       | `123`                  |
| `digit`                | Extracts digits only                              | `abc123xyz`               | `123`                  |
| `escape`               | Escapes HTML special characters                   | `<p>Hello &</p>`          | `&lt;p&gt;Hello &amp;&lt;/p&gt;` |
| `filter_if`            | Conditional check on array key/value              | `['key' => 'val']`        | `true`/`false`         |
| `format_date`          | Reformats a date string                           | `2023-01-15`              | `15/01/2023`           |
| `lowercase`            | Converts to lowercase                             | `HELLO`                   | `hello`                |
| `strip_tags`           | Removes HTML/PHP tags                             | `<b>Hello</b>`            | `Hello`                |
| `trim`                 | Trims whitespace                                  | `  hello  `               | `hello`                |
| `uppercase`            | Converts to uppercase                             | `hello`                   | `HELLO`                |
| `validate_email`       | Validates email format                            | `test@example.com`        | `test@example.com`     |
| `validate_url`         | Validates URL format                              | `https://example.com`     | `https://example.com`  |
| `cast_to_boolean`      | Casts input to boolean                            | `true`, `false`           | `true`, `false`        |
| `sanitize_number_int`  | Keeps only digits                                 | `abc123`                  | `123`                  |
| `sanitize_number_float`| Keeps digits and decimals                         | `abc12.3xyz`              | `12.3`                 |
| `remove_whitespace`    | Removes all whitespace                            | ` a b  c `                | `abc`                  |
| `slugify`              | Converts string into URL-friendly slug            | ` Hello World! `          | `hello-world`          |
| `normal_string`        | Strips tags and keeps `A-Z`, `0-9`, space, `-:_.` | `<script>alert(1)</script>` | `alert1`            |

### Chaining Filters
Chain multiple filters using the `|` separator:

```php
$result = FilterVar::filterValue('trim|uppercase|cast:string', '  hello world  ');
// Returns: "HELLO WORLD"
```

### Custom Filters
Add custom filters by editing `config/filter-var.php`:

```php
return [
    'custom_filters' => [
        'media_real_id' => \App\Filters\MediaRealId::class,
    ],
];
```

Define the custom filter class:

```php
namespace App\Filters;

use Aporat\FilterVar\Contracts\Filter;

class MediaRealId implements Filter
{
    public function apply(mixed $value, array $options = []): string
    {
        $value = (string) $value;
        return str_contains($value, '_') ? explode('_', $value, 2)[0] : $value;
    }
}
```

Use it:

```php
$result = FilterVar::filterValue('media_real_id', '11111_22222');
// Returns: "11111"
```

### Using Without Facade
Resolve from the container:

```php
$result = app('filter-var')->filterValue('trim', '  hello  ');
// Returns: "hello"
```

## Testing
Run the test suite:

```bash
composer test
```

Generate coverage reports:

```bash
composer test-coverage
```

## Contributing
Contributions are welcome! Please:
1. Fork the repository.
2. Create a feature branch (`git checkout -b feature/amazing-feature`).
3. Commit your changes (`git commit -m "Add amazing feature"`).
4. Push to the branch (`git push origin feature/amazing-feature`).
5. Open a pull request.

See [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License
This package is open-sourced under the [MIT License](LICENSE). See the [License File](LICENSE) for more information.

## Support
- **Issues**: [GitHub Issues](https://github.com/aporat/laravel-filter-var/issues)
- **Source**: [GitHub Repository](https://github.com/aporat/laravel-filter-var)
