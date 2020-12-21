<?php

namespace haythem\LaravelDatabaseQueueTracker\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelDatabaseQueueTracker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-database-queue-tracker';
    }
}
