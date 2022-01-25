<?php

namespace haythem\LaravelDatabaseQueueTracker\LivewireComponent;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Artisan;
use haythem\LaravelDatabaseQueueTracker\Models\QueueTracker as ModelsQueueTracker;

class QueueTrackerLivewireComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;

    public $jobId = 22;

    public $date = null;

    public $status;

    public $slectedQueue;


    protected $queryString = ['name', 'date', 'status'];

    public function render()
    {
        if (!$this->date) {
            $this->date = Carbon::now()->format('Y-m-d');
        }
        $queues = ModelsQueueTracker::where("status", 'like', '%' . $this->status . '%')->where("name", 'like', '%' . $this->name . '%')->whereDate('created_at', '=', $this->date)->orderBy("created_at", "desc")->paginate(50);
        return view('queue-tracker::livewire.queue-tracker', ["queues" => $queues]);
    }

    public function retry($id)
    {
        $queue = ModelsQueueTracker::find($id);
        $queue->is_loading = true;
        $queue->save();

        $failed_job = $queue->failedJob();
        try {
            Artisan::call('queue:retry ' . $failed_job->uuid);
        } catch (\Throwable $th) {
            Artisan::call('queue:retry ' . $failed_job->id);
        }
    }
    public function openModal($id)
    {
        //$this->jobId=$id;
        logger("Selected Queue tracker Id id : $id");
        $this->slectedQueue = ModelsQueueTracker::find($id);
    }
}
