@extends('layouts.app')

@section('content')
    {!! Form::open(['route' => 'daemon-login-post', 'method' => 'POST']) !!}

    <div class="form-group">
        {!! Form::label('code', 'Code') !!}
        {!! Form::text('code', null, ['class' => 'form-control']) !!}
    </div>

    <button class="btn btn-success" type="submit">Login</button>
    {!! Form::close() !!}
@endsection