@extends('layouts.app')

@section('content')
    Duration: {{ $duration }} days
    Total Value: ${{ round($totalValue / 100, 3) }}

    @foreach($items as $item)
        <p><img height="75px" src="http://steamcommunity-a.akamaihd.net/economy/image/{{ $item->icon_url }}">{{$item->market_name}}</p>
    @endforeach

    <p> Public ID: {{ $order->public_id }}</p>

    <p>Current state: {{ $steamOrder->stateText() }}</p>

    <p>Last update: {{ $steamOrder->updated_at->diffForHumans() }}</p>

    @if(!$order->confirmed)
        @if(!$steamOrder->notSent())
            <p>Trade Offer #{{ $steamOrder->tradeoffer_id }}</p>
            @if($steamOrder->active())
                <a href="https://steamcommunity.com/tradeoffer/{{ $steamOrder->tradeoffer_id }}">OPEN TRADE OFFER</a>
            @elseif($steamOrder->accepted())
                <a href="{{ route('create-confirmation', $order->public_id) }}">Create confirmation</a>
            @endif
        @else
            <a href="{{ route('send-trade-offer', $order->public_id) }}">Send Trade Link</a>
        @endif
    @else
        <p><a href="{{ route('view-confirmation', $order->confirmation->public_id) }}">Order is confirmed with ID: {{ $order->confirmation->public_id }}</a></p>
    @endif
@endsection