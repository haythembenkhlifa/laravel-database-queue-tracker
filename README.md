# Laravel Database Queue Tracker

[![Packagist](https://img.shields.io/packagist/v/haythem/laravel-database-queue-tracker.svg)](https://packagist.org/packages/haythem/laravel-database-queue-tracker)
[![Packagist](https://poser.pugx.org/haythem/laravel-database-queue-tracker/d/total.svg)](https://packagist.org/packages/haythem/laravel-database-queue-tracker)
[![Packagist](https://img.shields.io/packagist/l/haythem/laravel-database-queue-tracker.svg)](https://packagist.org/packages/haythem/laravel-database-queue-tracker)

Package description: this package allows you to track your database queued jobs
Supported laravel versions : 7/8
![alt text](https://github.com/haythembenkhlifa/laravel-database-queue-tracker/blob/master/src/img/animation.gif)

## Installation

Install via composer
```bash
composer require haythem/laravel-database-queue-tracker
```

Migrate
```bash
php artisan migrate
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

