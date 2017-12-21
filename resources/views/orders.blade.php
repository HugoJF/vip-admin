@extends('layouts.app')

@section('content')
    <h1>Current Orders</h1>
    @foreach($orders as $order)
        @if($order->isSteamOffer())
            <p><a href="{{ route('view-steam-order', $order->public_id) }}">{{ $order->public_id }} - {{ $order->orderable()->get()->first()->stateText() }}</a></p>
        @endif
    @endforeach
@endsection