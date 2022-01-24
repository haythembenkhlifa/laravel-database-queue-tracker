<?php

namespace haythem\LaravelDatabaseQueueTracker\Services;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Carbon\Exceptions\InvalidCastException;
use Illuminate\Support\Str;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobExceptionOccurred;
use haythem\LaravelDatabaseQueueTracker\Models\QueueTracker;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\InvalidCastException as EloquentInvalidCastException;

class QueueTrackerService
{
    public static $model;


    /**
     * Handle Processing Job.
     *
     * @param JobProcessed $event
     * @return void
     */
    public static function handleJobProcessing(JobProcessing $event): void
    {
        self::createQueueTracker($event->job);
    }



    /**
     * Handle Job Processed.
     *
     * @param JobProcessed $event
     * @return void
     */
    public static function handleJobProcessed(JobProcessed $event): void
    {
        self::markAsDone($event->job);
    }



    /**
     * Handle Job Failing.
     *
     * @param JobFailed $event
     * @return void
     */
    public static function handleJobFailed(JobFailed $event): void
    {
        self::markAsFailed($event->job, $event->exception);
    }



    /**
     * Handle Job Exception Occurred.
     *
     * @param JobExceptionOccurred $event
     * @return void
     */
    public static function handleJobExceptionOccurred(JobExceptionOccurred $event): void
    {
        self::markAsFailed($event->job, $event->exception);
    }



    /**
     * Find Job By Uuid.
     *
     * @param string $job_uuid
     * @return QueueTracker|null
     */
    public static function findQueueTracker($job_uuid)
    {
        return QueueTracker::where("job_uuid", $job_uuid)->first();
    }



    /**
     * Create New Queue Tracker Record.
     *
     * @param mixed $job
     * @return QueueTracker|null
     */
    public static function createQueueTracker($job)
    {
        if (!$job) return;

        // check whether this job failed before then we update the attemps
        $job_failed_before = self::findQueueTracker($job->uuid());

        $now = Carbon::now()->format('Y-m-d H:i:s.u');

        if ($job_failed_before) {
            $job_failed_before->job_id = $job->getJobId();
            $job_failed_before->name = $job->resolveName();
            $job_failed_before->queue = $job->getQueue();
            $job_failed_before->started_at = $now;
            $job_failed_before->status = QueueTracker::STATE_PROGRESS;
            $job_failed_before->attempt = $job->attempts();
            $job_failed_before->increment('tried');
            $job_failed_before->is_loading = false;
            $job_failed_before->save();

            return $job_failed_before;
        }
        $queuetracker = new QueueTracker();
        $queuetracker->job_id = $job->getJobId();
        $queuetracker->name = $job->resolveName();
        $queuetracker->queue = $job->getQueue();
        $queuetracker->started_at = $now;
        $queuetracker->job_uuid = $job->uuid();
        $queuetracker->attempt = $job->attempts();
        $queuetracker->is_loading = false;
        $queuetracker->save();
        return $queuetracker;
    }




    /**
     * Mark Queue Tracker Record As Failed.
     * 
     * @param mixed $job 
     * @param mixed $exception 
     * 
     * @return void|QueueTracker 
     * @throws InvalidFormatException 
     * @throws InvalidCastException 
     * @throws InvalidArgumentException 
     * @throws EloquentInvalidCastException 
     */
    public static function markAsFailed($job, $exception)
    {
        if (!$job) return;


        $job_failed_before = self::findQueueTracker($job->uuid());

        if ($job_failed_before) {

            $now = Carbon::now();

            $job_failed_before->finished_at = $now->format('Y-m-d H:i:s.u');

            $startedAt = $job_failed_before->started_at;

            $job_failed_before->processing_time =  (float) Carbon::parse($startedAt)->diffInSeconds($now) +  Carbon::parse($startedAt)->diff($now)->f;
            $job_failed_before->status = QueueTracker::STATE_FAILED;
            $job_failed_before->exception = $exception;
            $job_failed_before->is_loading = false;
            $job_failed_before->save();



            return $job_failed_before;
        }
    }



    /**
     * Mark Queue Tracker Record As Done.
     * 
     * @param mixed $job 
     * 
     * @return void|QueueTracker 
     * @throws InvalidFormatException 
     * @throws InvalidCastException 
     * @throws InvalidArgumentException 
     * @throws EloquentInvalidCastException 
     */
    public static function markAsDone($job)
    {
        if (!$job) return;

        $job_done = self::findQueueTracker($job->uuid());

        if ($job_done) {
            $now = Carbon::now();

            $job_done->finished_at = $now->format('Y-m-d H:i:s.u');

            $startedAt = $job_done->started_at;

            $job_done->processing_time =  (float) Carbon::parse($startedAt)->diffInSeconds($now) +  Carbon::parse($startedAt)->diff($now)->f;
            $job_done->status = QueueTracker::STATE_DONE;
            $job_done->is_loading = false;
            $job_done->save();


            return $job_done;
        }
    }
}
