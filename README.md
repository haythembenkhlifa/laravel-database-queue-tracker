# Laravel Database Queue Tracker

[![GitHub Workflow Status](https://github.com/haythem/laravel-database-queue-tracker/workflows/Run%20tests/badge.svg)](https://github.com/haythem/laravel-database-queue-tracker/actions)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)

[![Packagist](https://img.shields.io/packagist/v/haythem/laravel-database-queue-tracker.svg)](https://packagist.org/packages/haythem/laravel-database-queue-tracker)
[![Packagist](https://poser.pugx.org/haythem/laravel-database-queue-tracker/d/total.svg)](https://packagist.org/packages/haythem/laravel-database-queue-tracker)
[![Packagist](https://img.shields.io/packagist/l/haythem/laravel-database-queue-tracker.svg)](https://packagist.org/packages/haythem/laravel-database-queue-tracker)

Package description: this package allows you to track your database queued jobs

![alt text](https://github.com/haythembenkhlifa/laravel-database-queue-tracker/blob/master/src/img/animation.gif)

## Installation

Install via composer
```bash
composer require haythem/laravel-database-queue-tracker
```

### Publish package assets

```bash
php artisan vendor:publish --provider="haythem\LaravelDatabaseQueueTracker\ServiceProvider"
```

## Usage

1 - add IsTracked Trait to your job you want to track
```php
    use haythem\LaravelDatabaseQueueTracker\Traits\IsTracked;

    use IsTracked;
```

2 - add the this route to the web.php

```php
Route::get('/queue-tracker', function () {
    return view('queue-tracker::queue-tracker');
});
```


## Credits

- [](https://github.com/haythem/laravel-database-queue-tracker)
- [All contributors](https://github.com/haythem/laravel-database-queue-tracker/graphs/contributors)
