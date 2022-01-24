<?php

namespace haythem\LaravelDatabaseQueueTracker\Tests;

use haythem\LaravelDatabaseQueueTracker\Facades\LaravelDatabaseQueueTracker;
use haythem\LaravelDatabaseQueueTracker\ServiceProvider;
use PHPUnit\Framework\TestCase;

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
    /** @test */
    public function is_all_okay()
    {
        $this->assertEquals(1, 1);
    }
}
