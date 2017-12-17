@extends('layouts.app')

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        {!! Form::model($user, ['route' => ['settings.update', $user]]) !!}

        <div class="form-group">
            {!! Form::label('name', 'Name') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('tradeid', 'Trade ID') !!}
            {!! Form::text('tradeid', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('tradelink', 'Trade Link') !!}
            {!! Form::text('tradelink', null, ['class' => 'form-control']) !!}
        </div>

        <button class="btn btn-success" type="submit">Update</button>
        {!! Form::close() !!}
    </div>
@endsection