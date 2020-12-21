<?php

namespace haythem\LaravelDatabaseQueueTracker\Tests;

use haythem\LaravelDatabaseQueueTracker\Facades\LaravelDatabaseQueueTracker;
use haythem\LaravelDatabaseQueueTracker\ServiceProvider;
use Orchestra\Testbench\TestCase;

class LaravelDatabaseQueueTrackerTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'laravel-database-queue-tracker' => LaravelDatabaseQueueTracker::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
