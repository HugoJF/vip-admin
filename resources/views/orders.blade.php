@extends('layouts.app')

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1>Current Orders</h1>
        @foreach($orders as $order)
            @if($order->isSteamOffer())
                <p><a href="{{ route('view-steam-offer', $order->public_id) }}">{{ $order->public_id }} - {{ $order->orderable()->get()->first()->stateText() }}</a></p>
            @endif
        @endforeach
    </div>
@endsection