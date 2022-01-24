<?php

use haythem\LaravelDatabaseQueueTracker\Models\QueueTracker;

// Route::get('/queue-tracker',function(){
//     return view('queue-tracker::queue-tracker');
// });

Route::get('/queue/{id}', function ($id) {
    $queue = QueueTracker::find($id);
    dd($queue->exception);
    //return view('queue-tracker::queueerror',["queue"=>$queue]);
})->name("queue")->middleware(config('laravel-database-queue-tracker.middlewares'));
