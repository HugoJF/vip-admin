@extends('layouts.app')

@section('content')
    <h1>Current Orders</h1>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Order Public ID</th>
            @if($isAdmin)
            <th>Username</th>
            <th>Order Type</th>
            @endif
            <th>Duration</th>
            <th>State</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            @if($order->isSteamOffer())
                <tr>
                    <td scope="row"><a href="{{ route('view-steam-order', $order->public_id) }}">#{{ $order->public_id }}</a></td>
                    @if($isAdmin)
                        <td><a href="http://steamcommunity.com/profiles/{{ $order->user->steamid }}">{{ $order->user->username }}</a></td>
                        <td>{{ $order->orderable_type }}</td>
                    @endif
                    <td>{{ $order->duration }} {{ $order->duration == 1 ? 'day' : 'days' }}</td>
                    <td><span class="label label-{{ $order->orderable()->get()->first()->stateClass() }}">{{ $order->orderable()->get()->first()->stateText() }}</span></td>
                    <td><a class="btn btn-default" href="{{ route('view-steam-order', $order->public_id) }}">View order details</a></td>
                </tr>
            @else
                <tr>
                    <td scope="row"><a href="{{ route('view-token-order', $order->public_id) }}">#{{ $order->public_id }}</a></td>
                    @if($isAdmin)
                        <td><a href="http://steamcommunity.com/profiles/{{ $order->user->steamid }}">{{ $order->user->username }}</a></td>
                        <td>{{ $order->orderable_type }}</td>
                    @endif
                    <td>{{ $order->duration }} {{ $order->duration == 1 ? 'day' : 'days' }}</td>
                    <td><span class="label label-{{ $order->orderable()->first()->stateClass() }}">{{ $order->orderable()->first()->stateText() }}</span></td>
                    <td><a class="btn btn-default" href="{{ route('view-token-order', $order->public_id) }}">View order details</a></td>
                </tr>

            @endif
        @endforeach

        </tbody>
    </table>
@endsection