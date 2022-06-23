<div>
    <div class="my-5" 
    wire:poll.visible.500ms
    >
    
        
        <div class="flex justify-center dark:text-white">
            <h1 class="text-4xl font-bold">Database Queue Tracker</h1>
        </div>

        {{-- Summary   --}}
        <div class="flex flex-col w-full my-10 pl-1 dark:text-white">
            <h1 class="font-bold text-xl">Total Summary</h1>
            <div><b class="font-bold text-xl">Done : {{$done}}</b></div>
            <div><b class="font-bold text-xl">Failed : {{$failed}}</b></div>
            <div><b class="font-bold text-xl">In Progress : {{$inprogress}}</b></div>
        </div>


        {{-- Search inputs --}}
         <div class="mt-8 flex flex flex-col sm:flex-row w-full space-y-2 sm:space-y-0 sm:space-x-2  px-1">
            <input type="text" wire:model="name" class="rounded sm:w-64" placeholder="Search By Name" aria-describedby="basic-addon1">
            <select wire:model="status" class="rounded sm:w-64" placeholder="Search By Name">
                <option value="" selected>Select a status</option>
                <option value="Done">Done</option>
                <option value="In progress">In progress</option>
                <option value="Failed">Failed</option>
            </select>
            <input class="rounded w-full sm:w-64" wire:model="date" type="date">
            
        </div>


        {{-- Large devices --}}
        <div class="hidden lg:flex mt-10 w-full px-1">
            <table class="table-auto w-full border-y-separate [border-y-spacing:0.75rem] text-center">
                <thead class="">
                    <tr class="border h-12 text-xl align-middle dark:text-white">
                        {{-- <th>#</th> --}}
                        <th>Name</th>
                        <th>Queue</th>
                        <th>Attempts</th>
                        <th>Started At</th>
                        <th>Finished At</th>
                        <th>Running Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($queues as $queue)
                    <tr class="text-xl even:bg-white-100 odd:bg-gray-300 dark:even:bg-gray-400 h-12 hover:bg-gray-500 align-middle">
                        {{-- <td>{{$queue->id}}</td> --}}
                        <td><span>{{Illuminate\Support\Str::afterLast($queue->name,'\\')}}</span></td>
                        <td><span>{{ $queue->queue }}</span></td>
                        <td><span>{{ $queue->tried }}</span></td>
                        <td>{{substr($queue->started_at,0,19)}}</td>
                        <td>{{substr($queue->finished_at,0,19)}}</td>
                        <td>{{ sprintf('%02.2f', (float) $queue->processing_time) }} s</td>
                        @switch($queue->status)
                        @case(haythem\LaravelDatabaseQueueTracker\Models\QueueTracker::STATE_PROGRESS)
                        <td class=""><div class="flex flex-col items-center pr-4"><svg viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="currentColor" style="width: 30px;" class="ml-2">
                                <circle cx="15" cy="15" r="15">
                                    <animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate>
                                    <animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate>
                                </circle>
                                <circle cx="60" cy="15" r="9" fill-opacity="0.3">
                                    <animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9" calcMode="linear" repeatCount="indefinite"></animate>
                                    <animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite"></animate>
                                </circle>
                                <circle cx="105" cy="15" r="15">
                                    <animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate>
                                    <animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate>
                                </circle>
                            </svg>
                        </div></td>
                        <td></td>
                        @break
                        @case(haythem\LaravelDatabaseQueueTracker\Models\QueueTracker::STATE_DONE)
                        <td class=""><span class=" ml-2 bg-green-100 text-green-800 font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">Done</span></td>
                        <td></td>
                        @break
                        @default
                        <td ><span class="ml-2 bg-red-100 text-red-800 font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-red-200 dark:text-red-900">Failed</span></td>
                        <td >
                            <div class="flex justify-center">
                                <svg class="mx-3 mt-1" data-bs-toggle="modal" data-bs-target="#errorModal" wire:click="openErrorModal({{$queue->id}})" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="width: 1.25rem; height: 1.25rem;">
                                    <path d="M.2 10a11 11 0 0 1 19.6 0A11 11 0 0 1 .2 10zm9.8 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2a2 2 0 1 1 0-4 2 2 0 0 1 0 4z" />
                                    <title>View error</title>
                                </svg>

                                @if($queue->is_loading)
                                <svg  class="ml-3 mt-1 animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="width: 1.25rem; height: 1.25rem;">
                                    <path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z" />
                                    <title>Re-try</title>
                                </svg>
                                @else
                                <svg wire:click="retry({{ $queue->id }})" onclick="this.disabled = true;this.hidden = true;this.style.display = 'none'" class="ml-3 mt-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="width: 1.25rem; height: 1.25rem;">
                                    <path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z" />
                                    <title>Re-try</title>
                                </svg>
                                @endif
                            </div>
                        </td>
                        @endswitch
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>


        {{-- Smaller devices --}}
        <div class="flex flex-col lg:hidden w-full px-1 dark:text-white">
                @foreach ($queues as $queue)
                <div class="border-2 rounded mt-2 p-2 flex flex-col border-red-400
                @if($queue->status === haythem\LaravelDatabaseQueueTracker\Models\QueueTracker::STATE_DONE) border-green-400 @endif
                @if($queue->status === haythem\LaravelDatabaseQueueTracker\Models\QueueTracker::STATE_PROGRESS) border-gray-400 @endif
                text-xl">
                    <div>
                        <b>Status : </b><span>{{$queue->status}}</span>
                    </div>
                    <div>
                        <b>Name : </b><span>{{Illuminate\Support\Str::afterLast($queue->name,'\\')}}</span>
                    </div>
                    <div>
                        <b>Queue : </b><span>{{$queue->queue}}</span>
                    </div>
                    <div>
                        <b>Attempts : </b><span>{{$queue->tried}}</span>
                    </div>
                    <div>
                        <b>Started At : </b><span>{{substr($queue->started_at,0,19)}}</span>
                    </div>
                    <div>
                        <b>Finished At : </b><span>{{substr($queue->finished_at,0,19)}}</span>
                    </div>                    
                    <div>
                        <b>Running Time: </b><span>{{ sprintf('%02.2f', (float) $queue->processing_time) }} s</span>
                    </div>   

                    @switch($queue->status)
                    @case(haythem\LaravelDatabaseQueueTracker\Models\QueueTracker::STATE_PROGRESS)
                    <div class="flex flex-col items-center pr-4 mt-4"><svg viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="currentColor" style="width: 60px;" class="ml-2">
                            <circle cx="15" cy="15" r="15">
                                <animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate>
                                <animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate>
                            </circle>
                            <circle cx="60" cy="15" r="9" fill-opacity="0.3">
                                <animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9" calcMode="linear" repeatCount="indefinite"></animate>
                                <animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite"></animate>
                            </circle>
                            <circle cx="105" cy="15" r="15">
                                <animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate>
                                <animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate>
                            </circle>
                        </svg>
                    </div>
                    @break
                    @case(haythem\LaravelDatabaseQueueTracker\Models\QueueTracker::STATE_DONE)
                    @break
                    @default
                        <div class="flex justify-center">
                            <svg class="ml-3 mr-20 mt-1 dark:fill-white" data-bs-toggle="modal" data-bs-target="#errorModal" wire:click="openErrorModal({{$queue->id}})" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="width: 1.25rem; height: 1.25rem;">
                                <path d="M.2 10a11 11 0 0 1 19.6 0A11 11 0 0 1 .2 10zm9.8 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2a2 2 0 1 1 0-4 2 2 0 0 1 0 4z" />
                                <title>View error</title>
                            </svg>

                            @if($queue->is_loading)
                            <svg  class="mt-1 animate-spin dark:fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="width: 1.25rem; height: 1.25rem;">
                                <path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z" />
                                <title>Re-try</title>
                            </svg>
                            @else
                            <svg class="mt-1 dark:fill-white" wire:click="retry({{ $queue->id }})" onclick="this.disabled = true;this.hidden = true;this.style.display = 'none'"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="width: 1.25rem; height: 1.25rem;">
                                <path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z" />
                                <title>Re-try</title>
                            </svg>
                            @endif
                        </div>
                    @endswitch

                </div>
                @endforeach
        </div>


        @if (count($queues)==0)
        <div class="flex flex-row justify-center mt-5">
            <h1 class="text-2xl dark:text-white">Nothing Found :(</h1>
        </div>
        @endif

        {{-- Pagination --}}
        <div class="mx-1 mt-5">
            {{ $queues->links() }}
        </div>

    </div>

        {{-- Error  Modal  --}}
        @if($selectedQueue)
            <div class="fixed inset-0 bg-gray-600  overflow-y-auto h-full w-full mx-auto items-center justify-center">
                <div class="bg-gray-600 m-4 px-16 py-14 rounded-md text-center w-full"   >
                        <div class="flex flex-col ">
                            <div class="flex flex-col text-left " >
                                <span class="font-bold text-sm" >{{$selectedQueue->exception}}</span>
                            </div>
                            <div class="flex mt-4 justify-start lg:justify-end">  
                                <button wire:click="closeErrorModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Close</button>    
                            </div>
                        </div>
                </div>
            </div>
        @endif

</div>





