@extends('layouts.app')

@section('content')
    <h1>Current Orders</h1>

    <table class="table table-bordered {{ isset($highlight) ? '' : 'table-striped ' }}">
        <thead>
        <tr>
            <th>Order Public ID</th>
            @if($isAdmin)
            <th>Username</th>
            <th>Order Type</th>
            @endif
            <th>Duration</th>
            <th>Extra tokens</th>
            <th>State</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr {{ isset($highlight) && $order->user->steamid == $highlight ? 'class=info' : '' }}>
                <!-- Order Public ID -->
                <td scope="row"><a href="{{ route(($order->isSteamOffer() ? 'steam' : 'token') . '-order.show', $order->public_id) }}"><code>#{{ $order->public_id }}</code></a></td>

                <!-- Username and Order Type -->
                @if($isAdmin)
                    <td>
                        <a href="http://steamcommunity.com/profiles/{{ $order->user->steamid }}">{{ $order->user->username }}</a>
                        <a href="?highlight={{ $order->user->steamid }}"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
                    </td>
                    <td>{{ $order->orderable_type }}</td>
                @endif

                <!-- Duration -->
                <td>{{ $order->duration }} {{ $order->duration == 1 ? 'day' : 'days' }}</td>

                <!-- Extra tokens -->
                <td>{{ $order->extra_tokens ?? '0' }} tokens</td>

                <!-- State -->
                <td><span class="label label-{{ $order->orderable->stateClass() }}">{{ $order->orderable->stateText() }}</span></td>

                <!-- Actions -->
                <td>
                    <a class="btn btn-default" href="{{ route(($order->isSteamOffer() ? 'steam' : 'token') . '-order.show', $order->public_id) }}">View order details</a>
                    @if($order->isSteamOffer() && !$order->orderable->tradeoffer_id && $isAdmin)
                        <a class="btn btn-primary" href="{{ route('steam-order.send-tradeoffer', $order->public_id) }}">Send Trade Offer</a>
                    @endif
                </td>

            </tr>
        @endforeach

        </tbody>
    </table>
@endsection