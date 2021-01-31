<div x-data="{ open: false}">
    {{-- <div class="mt-5" wire:poll.500ms> --}}
        <div class="mt-5">
            <div class="d-flex justify-content-center mb-5">
                <h1>Database Queue Tracker</h1>
            </div>
            <div class="d-none d-sm-inline-flex w-100">
                <div class="row row-cols-sm-auto g-3 align-items-center w-100 p-0 m-0">
                    <div class="mb-3 mr-sm-2 mr-2 p-0">
                        <input type="text"  wire:model="name" class="form-control" placeholder="Search By Name"  aria-describedby="basic-addon1">
                    </div>
                    <div class="mb-3 mr-lg-2">
                            <select wire:model="status" class="form-control" placeholder="Search By Name" >
                                <option value="" selected>All</option>
                                <option value="Done">Done</option>
                                <option value="In progress">In progress</option>
                                <option value="Failed">Failed</option>
                            </select>
                    </div>
                    <div class="mb-3">
                        <input  class="form-control" wire:model="date" type="date">
                    </div>
                </div>
            </div>
            <div class="d-inline d-sm-none w-100">
                <div class="row row-cols-sm-auto g-3 align-items-center w-100 p-0 m-0">
                    <div class="mb-3 mr-sm-2 mr-2 p-0">
                        <input type="text"  wire:model="name" class="form-control" placeholder="Search By Name"  aria-describedby="basic-addon1">
                    </div>
                    <div class="mb-3 mr-lg-2 p-0">
                            <select wire:model="status" class="form-control" placeholder="Search By Name" >
                                <option value="" selected>All</option>
                                <option value="Done">Done</option>
                                <option value="In progress">In progress</option>
                                <option value="Failed">Failed</option>
                            </select>
                    </div>
                    <div class="mb-3 p-0">
                        <input  class="form-control" wire:model="date" type="date">
                    </div>
                </div>
            </div>
        <div class="w-100 d-none  d-lg-block">
            <table class="table table-striped table-hover">
                <thead class="fw-bold">
                    <td scope="col">#</td>
                    <td scope="col">Name</td>
                    <td scope="col">Details</td>
                    <td scope="col">Started At</td>
                    <td scope="col">Finished At</td>
                    <td scope="col">Running Time</td>
                    <td scope="col">Status</td>
                    <td scope="col">Actions</td>
                </thead>
                <tbody>
                    @foreach ($queues as $queue)
                    <tr>
                        <td>{{$queue->id}}</td>
                        <td class="text-nowrap">{{$queue->name}}</td>
                        <td class="text-nowrap"><span class="badge bg-info">Queue : {{ $queue->queue }} | Attempt : {{$queue->attempt}} | Tried : {{$queue->tried}}</span></td>
                        <td class="text-nowrap">{{substr($queue->started_at,0,19)}}</td>
                        <td class="text-nowrap">{{substr($queue->finished_at,0,19)}}</td>
                        <td class="text-nowrap">{{ sprintf('%02.2f', (float) $queue->processing_time) }} s</td>
                        @switch($queue->status)
                            @case(haythem\LaravelDatabaseQueueTracker\Models\QueueTracker::STATE_PROGRESS)
                                <td class="text-center"><svg viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="currentColor" style="width: 30px;"><circle cx="15" cy="15" r="15"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate></circle><circle cx="60" cy="15" r="9" fill-opacity="0.3"><animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite"></animate></circle><circle cx="105" cy="15" r="15"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate></circle></svg></td>
                                <td></td>
                                @break
                            @case(haythem\LaravelDatabaseQueueTracker\Models\QueueTracker::STATE_DONE)
                                <td class="text-center"><span class="badge bg-success">Done</span></td>
                                <td></td>

                                @break
                            @default
                            <td class="text-center"><span class="badge bg-danger">Failed</span></td>
                            <td class="text-nowrap">

                                <svg class="ml-3 mt-1" data-bs-toggle="modal" data-bs-target="#errorModal" onclick="view({{$queue}})"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="width: 1.25rem; height: 1.25rem;"><path d="M.2 10a11 11 0 0 1 19.6 0A11 11 0 0 1 .2 10zm9.8 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>

                                @if($queue->is_loading)
                                <svg  wire:click="retry({{ $queue->id }})" disabled class="ml-3 mt-1 rotation"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="width: 1.25rem; height: 1.25rem;"><path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z"/></svg>
                                @else
                                <svg  wire:click="retry({{ $queue->id }})" disabled onclick="this.disabled = true;this.hidden = true;this.style.display = 'none'"  class="ml-3 mt-1"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="width: 1.25rem; height: 1.25rem;"><path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z"/></svg>
                                @endif
                            </td>
                        @endswitch
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="w-100 d-block  d-lg-none">
            <div class="list-group">
                @foreach ($queues as $queue)
                <div class="list-group-item border rounded mt-2">
                  <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{Illuminate\Support\Str::afterLast($queue->name,'\\')}}</h5>
                    <small><b>{{ sprintf('%02.2f', (float) $queue->processing_time) }} s</b></small>
                  </div>
                  <span class="badge bg-info">Queue : {{ $queue->queue }} | Attempt : {{$queue->attempt}} | Tried : {{$queue->tried}}</span>
                  <p><b class="mb-1">{{substr($queue->started_at,11,8)}} - {{substr($queue->finished_at,11,8)}}</b></p>




                @switch($queue->status)
                        @case(haythem\LaravelDatabaseQueueTracker\Models\QueueTracker::STATE_PROGRESS)
                            <svg viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="currentColor" style="width: 30px;"><circle cx="15" cy="15" r="15"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate></circle><circle cx="60" cy="15" r="9" fill-opacity="0.3"><animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite"></animate></circle><circle cx="105" cy="15" r="15"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate></circle></svg>

                            @break
                        @case(haythem\LaravelDatabaseQueueTracker\Models\QueueTracker::STATE_DONE)
                            <span class="badge bg-success">Done</span>

                            @break
                        @default
                        <span class="badge bg-danger mr-3">Failed</span>
                            <svg class="mr-3" data-bs-toggle="modal" data-bs-target="#errorModal" onclick="view({{$queue}})"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="width: 1.25rem; height: 1.25rem;"><path d="M.2 10a11 11 0 0 1 19.6 0A11 11 0 0 1 .2 10zm9.8 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>

                            @if($queue->is_loading)
                            <svg  wire:click="retry({{ $queue->id }})" disabled class="mr-3 rotation"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="width: 1.25rem; height: 1.25rem;"><path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z"/></svg>
                            @else
                            <svg  wire:click="retry({{ $queue->id }})" disabled onclick="this.disabled = true;this.hidden = true;this.style.display = 'none'"  class="mr-3"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="width: 1.25rem; height: 1.25rem;"><path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z"/></svg>
                            @endif
                @endswitch













                </div>
                @endforeach
              </div>

        </div>

    @if (count($queues)==0)
    <div class="pagination justify-content-center">
        <h1>Nothing Found :(</h1>
    </div>
    @endif
    <div class="pagination d-flex justify-content-center mt-1">
        <div class=" overflow-auto">
            {{ $queues->links() }}
        </div>

    </div>

    </div>


</div>



<!-- Modal -->
<div wire:ignore.self class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <pre id="errorModalBody"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
       </div>
    </div>
</div>

<script>
function view(queue) {
    document.getElementById("errorModalTitle").innerHTML='#'+queue.id+' | Job Id :'+queue.job_id;
    document.getElementById("errorModalBody").innerHTML=queue.exception;
}
</script>

