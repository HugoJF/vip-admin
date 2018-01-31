@extends('layouts.app')

@section('content')
    {!! Form::open(['route' => 'opskins-update-form-post', 'method' => 'POST', 'files' => true]) !!}

    <div class="form-group">
        {!! Form::label('data', 'Data') !!}
        {!! Form::file('data', null, ['class' => 'form-control']) !!}
    </div>
    <button class="btn btn-success" type="submit">Update OPSkins data</button>

    {!! Form::close() !!}


@endsection