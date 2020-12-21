<?php

namespace haythem\LaravelDatabaseQueueTracker;

use Livewire\Livewire;
use Illuminate\Queue\QueueManager;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobExceptionOccurred;
use haythem\LaravelDatabaseQueueTracker\Services\QueueTrackerService;
use haythem\LaravelDatabaseQueueTracker\Models\QueueTracker as ModelsQueueTracker;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/laravel-database-queue-tracker.php';

    public function boot()
    {
        Livewire::component('queue-tracker',LivewireComponent\QueueTrackerLivewireComponent::class);


        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([ self::CONFIG_PATH => config_path('laravel-database-queue-tracker.php'), ], 'config');

        $this->loadViewsFrom(__DIR__ .'/../views','queue-tracker');

        $this->loadRoutesFrom(__DIR__.'/../src/routes/web.php');

        /** @var QueueManager $manager */
        $manager = app(QueueManager::class);

        $manager->before(static function (JobProcessing $event) {
            QueueTrackerService::handleJobProcessing($event);
        });

        $manager->after(static function (JobProcessed $event) {
            QueueTrackerService::handleJobProcessed($event);
        });

        $manager->failing(static function (JobFailed $event) {
            QueueTrackerService::handleJobFailed($event);
        });

        $manager->exceptionOccurred(static function (JobExceptionOccurred $event) {
            QueueTrackerService::handleJobExceptionOccurred($event);
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'laravel-database-queue-tracker'
        );

        $this->app->bind('laravel-database-queue-tracker', function () {
            return new LaravelDatabaseQueueTracker();
        });

        QueueTrackerService::$model = config('laravel-database-queue-tracker.model') ?: ModelsQueueTracker::class;

    }
}
