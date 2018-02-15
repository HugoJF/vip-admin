@extends('layouts.app')

@section('content')
    <h1>Token-order creation</h1>
    {!! Form::open(['route' => 'token-orders.create', 'method' => 'GET']) !!}

    <div class="form-group">
        {!! Form::label('token', 'Token') !!}
        {!! Form::text('token', null, ['class' => 'form-control']) !!}
    </div>
    <button id="use-token" class="btn btn-success" type="submit">Use token</button>

    {!! Form::close() !!}


@endsection