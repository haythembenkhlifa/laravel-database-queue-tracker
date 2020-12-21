@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h5>{{ json_encode($queue->exception)}}</h5>
        </div>
    </div>
</div>
@endsection
