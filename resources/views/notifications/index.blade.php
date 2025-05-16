@extends('adminlte::page')

@section('title', 'Notifications')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Notifications</h3>
        </div>
        <div class="card-body">
            @foreach($notifications as $notification)
                <div class="alert alert-info">
                    <i class="fas fa-bell mr-2"></i> {{ $notification->data['message'] }}
                    <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    </div>
@stop
