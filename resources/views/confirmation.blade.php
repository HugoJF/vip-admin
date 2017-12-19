@extends('layouts.app')

@section('content')
    <h1>Confirmation #{{ $confirmation->public_id }}</h1>

    <p>Start period: {{ $confirmation->start_period }}</p>
    <p>End period {{ $confirmation->end_period }}</p>
    <p><a href="{{ route('view-steam-offer', $order->public_id) }}">Order ID: {{ $order->public_id }}</a></p>

@endsection