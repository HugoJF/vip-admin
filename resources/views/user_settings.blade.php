@extends('layouts.app')

@section('content')
    <h1>@lang('messages.user-settings')</h1>
    <br>

    {!! Form::model($user, ['route' => ['users.settings.update', $user]]) !!}

    <div class="form-group">
        {!! Form::label('name', trans('messages.name')) !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
        <p class="help-block">@lang('messages.user-settings-name-help')</p>
    </div>

    <div class="form-group">
        {!! Form::label('tradelink', trans('messages.trade-link')) !!}
        {!! Form::text('tradelink', null, ['class' => 'form-control']) !!}
        <p class="help-block">@lang('messages.user-settings-tradelink-help')</p>
    </div>

    <div class="form-group">
        {!! Form::label('email', trans('messages.email')) !!}
        {!! Form::email('email', null, ['class' => 'form-control']) !!}
        <p class="help-block">@lang('messages.user-settings-email-help')</p>
    </div>
    
    <div class="form-group">
        {!! Form::label('lang', trans('messages.lang')) !!}
        {!! Form::select('lang', ['en' => trans('messages.english'), 'pt_BR' => trans('messages.portuguese')], null, ['class' => 'form-control']) !!}
        <p class="help-block">@lang('messages.user-settings-lang-help')</p>
    </div>

    <button id="submit" class="btn btn-success" type="submit">@lang('messages.save')</button>
    {!! Form::close() !!}
@endsection
