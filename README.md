# Laravel Filter Var

[![Software License][ico-license]](LICENSE)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-build]](https://github.com/aporat/laravel-filter-var/actions)

Laravel package for filtering and sanitizing request variables

## Installation

The filter-var service provider can be installed via [Composer](https://getcomposer.org/).

```
composer require aporat/laravel-filter-var
```

To use the FilterVar service provider, you must register the provider when bootstrapping your application.

### Laravel

#### Laravel 5.5 and above

The package will automatically register provider and facade.

#### Laravel 5.4 and below

Add `Aporat\FilterVar\Laravell\FilterVarServiceProvider` to the `providers` section of your `config/app.php`:

```php
    'providers' => [
        // ...
        Aporat\FilterVar\Laravel\FilterVarServiceProvider::class,
    ];
```

Add FilterVar facade to the `aliases` section of your `config/app.php`:

```php
    'aliases' => [
        // ...
        'FilterVar' => Aporat\FilterVar\Laravel\Facade\FilterVar::class,
    ];
```

Or use the facade class directly:

```php
  use Aporat\FilterVar\Laravel\Facade\FilterVar;
```

Now run `php artisan vendor:publish` to publish `config/filter-var.php` file in your config directory.

#### Lumen

Register the `Aporat\FilterVar\Laravel\FilterVarServiceProvider` in your `bootstrap/app.php`:

```php
    $app->register(Aporat\FilterVar\Laravel\FilterVarServiceProvider::class);
```

Copy the `filter-var.php` config file in to your project:

```
    mkdir config
    cp vendor/aporat/laravel-filter-var/Config/filter-var.php config/filter-var.php
```


## Configuration

In the config file you can set custom filters

## Usage

Filter and escape user agent header:
```php
$user_agent = FilterVar::filterValue('cast:string|trim|strip_tags|escape', $request->header('User-Agent'));
```

## Credits

- [Adar Porat][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/aporat/laravel-filter-var.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-build]: https://img.shields.io/travis/aporat/laravel-filter-var/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/aporat/laravel-filter-var.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/aporat/laravel-filter-var.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/aporat/laravel-filter-var.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/aporat/laravel-filter-var
[link-travis]: https://travis-ci.org/aporat/laravel-filter-var
[link-scrutinizer]: https://scrutinizer-ci.com/g/aporat/laravel-filter-var/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/aporat/laravel-filter-var
[link-downloads]: https://packagist.org/packages/aporat/laravel-filter-var
[link-author]: https://github.com/aporat
[link-contributors]: ../../contributors
