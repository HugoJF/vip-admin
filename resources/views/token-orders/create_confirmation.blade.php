@extends('layouts.app')

@section('content')
    {!! Form::open(['route' => 'token-orders.store', 'method' => 'POST']) !!}

    <input type="hidden" name="token" value="{{ $token->token }}">
    <h2>@lang('messages.viewing-token'): <strong>{{ $token->token }}</strong></h2>

    <table class="table table-hover">
        <tbody>
        <tr>
            <td>{{ trans_choice('messages.token', 2) }}</td>
            <td><span class="label label-success">{{ $token->token}}</span></td>
        </tr>
        <tr>
            <td>@lang('messages.duration')</td>
            <td><span class="label label-success">{{ $token->duration }} {{ strtolower(trans_choice('messages.time.days', $token->duration)) }}</span></td>
        </tr>
        <tr>
            <td>@lang('messages.expiration')</td>
            <td><span class="label label-success">${{ $token->expiration }} {{ strtolower(trans_choice('messages.time.hours', $token->expiration)) }}</span></td>
        </tr>
        <tr>
            <td>@lang('messages.expiration-date')</td>
            <td><span class="label label-success">{{ \Carbon\Carbon::now()->addHours($token->expiration) }}</span></td>
        </tr>
        <tr>
            <td>@lang('messages.expiration-remaining')</td>
            <td><span class="label label-success">{{ \Carbon\Carbon::now()->addHours($token->expiration)->diffForHumans() }}</span></td>
        </tr>
        <tr>
            <td>@lang('messages.note')</td>
            <td><span class="label label-success">{{ $token->note }}</span></td>
        </tr>
        <tr>
            <td>@lang('messages.state')</td>
            <td><span class="label label-{{ $token->status()['class'] }}">{{ $token->status()['text'] }}</span></td>
        </tr>
        </tbody>
    </table>

    <button id="confirm" class="btn btn-success btn-block" type="submit">@lang('messages.confirm-token')</button>

    {!! Form::close() !!}


@endsection