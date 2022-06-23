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

    protected $paginationTheme = 'tailwind';

    public $name;

    public $jobId = 22;

    public $date = null;

    public $status;

    public $selectedQueue;


    protected $queryString = ['name', 'date', 'status'];

    public function render()
    {
        if (!$this->date) {
            $this->date = Carbon::now()->format('Y-m-d');
        }
        $allQueues = collect(ModelsQueueTracker::get());
        $failed = $allQueues->where("status", ModelsQueueTracker::STATE_FAILED)->count();
        $done = $allQueues->where("status", ModelsQueueTracker::STATE_DONE)->count();
        $inprogress = $allQueues->where("status", ModelsQueueTracker::STATE_PROGRESS)->count();
        $perPage = config('laravel-database-queue-tracker.per_page', 2);
        $queues = ModelsQueueTracker::where("status", 'like', '%' . $this->status . '%')
            ->where("name", 'like', '%' . $this->name . '%')
            ->whereDate('created_at', '=', $this->date)
            ->orderBy("created_at", "desc")
            ->paginate($perPage);
        return view('queue-tracker::livewire.queue-tracker', ["queues" => $queues, "done" => $done, "failed" => $failed, "inprogress" => $inprogress,]);
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

    public function updatingName(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingDate(): void
    {
        $this->resetPage();
    }

    public function openErrorModal($id)
    {
        $this->selectedQueue = ModelsQueueTracker::find($id);
    }

    public function closeErrorModal()
    {
        $this->selectedQueue = null;
    }
}
