@extends('layouts.app')

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1>Confirmation #{{ $confirmation->public_id }}</h1>

        <p>Start period: {{ $confirmation->start_period }}</p>
        <p>End period {{ $confirmation->end_period }}</p>
        <p><a href="{{ route('view-steam-offer', $order->public_id) }}">Order ID: {{ $order->public_id }}</a></p>

    </div>
@endsection