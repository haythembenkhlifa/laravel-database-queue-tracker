<?php

namespace haythem\LaravelDatabaseQueueTracker\Models;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

Class QueueTracker extends Model
{
    protected $table = "queue_tracker";

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];


    CONST STATE_PROGRESS = "In progress";
    CONST STATE_FAILED = "Failed";
    CONST STATE_DONE = "Done";

    public function failedJob()
    {
       return DB::table('failed_jobs')->where('payload','LIKE','%'.$this->job_uuid.'%')->first();
    }
}
