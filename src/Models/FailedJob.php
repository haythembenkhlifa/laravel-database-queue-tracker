<?php

namespace haythem\LaravelDatabaseQueueTracker\Models;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class FailedJob extends Model
{
    protected $table = "failed_jobs";

    public $fillable = [];
}
