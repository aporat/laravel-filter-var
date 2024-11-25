# Laravel Filter Var

[![codecov](https://codecov.io/github/aporat/laravel-filter-var/graph/badge.svg?token=VPCAXPZUBP)](https://codecov.io/github/aporat/laravel-filter-var)
[![StyleCI](https://github.styleci.io/repos/288753189/shield?branch=master)](https://github.styleci.io/repos/288753189?branch=master)
[![Latest Version](http://img.shields.io/packagist/v/aporat/laravel-filter-var.svg?style=flat-square&logo=composer)](https://packagist.org/packages/aporat/laravel-filter-var)
[![Latest Dev Version](https://img.shields.io/packagist/vpre/aporat/laravel-filter-var.svg?style=flat-square&logo=composer)](https://packagist.org/packages/aporat/laravel-filter-var#dev-develop)
[![Monthly Downloads](https://img.shields.io/packagist/dm/aporat/laravel-filter-var.svg?style=flat-square&logo=composer)](https://packagist.org/packages/aporat/laravel-filter-var)


Laravel package for filtering and sanitizing request variables

## Installation

The filter-var service provider can be installed via [Composer](https://getcomposer.org/).

```
composer require aporat/laravel-filter-var
```

To use the FilterVar service provider, you must register the provider when bootstrapping your application.

## Usage

Filter and escape user agent header:
```php
$user_agent = FilterVar::filterValue('cast:string|trim|strip_tags|escape', $request->header('User-Agent'));
```

## License]()

The MIT License (MIT). Please see [License File](LICENSE) for more information.
