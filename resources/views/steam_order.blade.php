@extends('layouts.app')

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        Duration: {{ $duration }} days
        Total Value: ${{ round($totalValue / 100, 2) }}

        @foreach($items as $item)
            <p>{{$item->market_name}}</p>
        @endforeach

        <p> Public ID: {{ $public_id }}</p>
        <p>Current state: {{ $steamOrder->stateText() }}</p>

        @if(!$steamOrder->notSent())
            <p>Trade Offer #{{ $steamOrder->tradeoffer_id }}</p>
            @if($steamOrder->active())
                <a href="https://steamcommunity.com/tradeoffer/{{ $steamOrder->tradeoffer_id }}">OPEN TRADE OFFER</a>
            @endif
        @else
            <a href="{{ route('send-trade-offer', $public_id) }}">Send Trade Link</a>
        @endif

    </div>
@endsection