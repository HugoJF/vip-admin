@extends('layouts.app')

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        Duration: {{ $duration }} days
        Total Value: ${{ round($totalValue / 100, 2) }}

        @foreach($items as $item)
            <p>{{$item->market_name}}</p>
        @endforeach

        <p> Public ID: {{ $order->public_id }}</p>

        <p>Current state: {{ $steamOrder->stateText() }}</p>

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

    </div>
@endsection