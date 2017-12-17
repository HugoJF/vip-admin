@extends('layouts.app')

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        Duration: {{ $duration }}
        Total Value: {{ $totalValue }}

        @foreach($items as $item)
            <p>{{$item->market_name}}</p>
        @endforeach

        <p> Public ID: {{ $public_id }}</p>

        @if($steamOrder->tradeoffer_id)
            <p>Trade Offer #{{ $steamOrder->tradeoffer_id }}</p>
            <p>Current state: {{ $steamOrder->stateText() }}</p>
            @if($steamOrder->active())
                <a href="https://steamcommunity.com/tradeoffer/{{ $steamOrder->tradeoffer_id }}">OPEN TRADE OFFER</a>
            @endif
        @endif

        @if($steamOrder->notSent())
            <a href="{{ route('send-trade-offer', $public_id) }}">Send Trade Link</a>
        @endif

    </div>
@endsection