@extends('layouts.app')

@section('content')
    {!! Form::open(['route' => 'token-order.create', 'method' => 'GET']) !!}

    <div class="form-group">
        {!! Form::label('token', 'Token') !!}
        {!! Form::text('token', null, ['class' => 'form-control']) !!}
    </div>
    <button id="use-token" class="btn btn-success" type="submit">Use token</button>

    {!! Form::close() !!}


@endsection