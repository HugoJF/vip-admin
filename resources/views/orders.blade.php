@extends('layouts.app')

@section('content')
    <h1>Current Orders</h1>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Order Public ID</th>
            <th>State</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            @if($order->isSteamOffer())
                <tr>
                    <th scope="row"><a href="{{ route('view-steam-order', $order->public_id) }}">#{{ $order->public_id }}</a></th>
                    <td><span class="label label-{{ $order->orderable()->get()->first()->stateClass() }}">{{ $order->orderable()->get()->first()->stateText() }}</span></td>
                    <td><a class="btn btn-default" href="{{ route('view-steam-order', $order->public_id) }}">View order details</a></td>
                </tr>
            @endif
        @endforeach

        </tbody>
    </table>
@endsection