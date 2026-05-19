@extends('layouts.app')

@section('title', 'Notifications')
@section('page_title', 'Notifications')

@section('content')

<div class="card">
    <div class="card-body">

        <div style="margin-bottom:16px;">
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button class="btn btn-primary">
                    Mark all as read
                </button>
            </form>
        </div>

        @forelse($notifications as $notification)

            <div class="card" style="margin-bottom:12px;">

                <div class="card-body">

                    <div style="display:flex; justify-content:space-between; gap:12px;">

                        <div>
                            <h4 style="margin:0;">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </h4>

                            <p style="margin-top:8px;">
                                {{ $notification->data['message'] ?? '' }}
                            </p>

                            <small>
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>

                        <div>

                            @if(is_null($notification->read_at))

                                <form method="POST"
                                      action="{{ route('notifications.read', $notification->id) }}">

                                    @csrf

                                    <button class="btn btn-sm btn-primary">
                                        Mark as read
                                    </button>

                                </form>

                            @endif

                        </div>

                    </div>

                    @if(!empty($notification->data['url']))
                        <div style="margin-top:12px;">
                            <a href="{{ $notification->data['url'] }}">
                                Open
                            </a>
                        </div>
                    @endif

                </div>

            </div>

        @empty

            <p>No notifications found.</p>

        @endforelse

        {{ $notifications->links() }}

    </div>
</div>

@endsection