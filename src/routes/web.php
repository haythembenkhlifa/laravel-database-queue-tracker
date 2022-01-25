<?php

use haythem\LaravelDatabaseQueueTracker\Models\QueueTracker;


// This route giving me an issue when adding it to the boot method of the service provider.

// Route::get('/queue-tracker', function () {
//     return view('queue-tracker::layout');
// })->name('queue-tracker');





Route::get('/queue/{id}', function ($id) {
    $queue = QueueTracker::find($id);
    dd($queue->exception);
    //return view('queue-tracker::queueerror',["queue"=>$queue]);
})->name("queue")->middleware(config('laravel-database-queue-tracker.middlewares'));
