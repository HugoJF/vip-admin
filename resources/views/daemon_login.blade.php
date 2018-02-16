@extends('layouts.app')

@section('content')
    <h1>@lang('messages.daemon-login')</h1>
    {!! Form::open(['route' => 'daemon-login-post', 'method' => 'POST']) !!}

    <div class="form-group">
        {!! Form::label('code', 'Code', ['class' => 'control-label']) !!}
        {!! Form::text('code', null, ['class' => 'form-control']) !!}
    </div>

    <button class="btn btn-success" type="submit">@lang('messages.login')</button>
    {!! Form::close() !!}
@endsection