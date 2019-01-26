@extends('layouts.app')

@section('content')
    
    <h2>@lang('messages.viewing-token'): <strong>{{ $token->token }}</strong></h2>
    
    <table class="table table-hover">
        <tbody>
        <tr>
            <td>@lang('messages.token')</td>
            <td><span class="label label-success">{{ $token->token}}</span></td>
        </tr>
        <tr>
            <td>@lang('messages.duration')</td>
            <td><span class="label label-success">{{ $token->duration }} @lang('messages.time.days', $token->duration)</span></td>
        </tr>
        <tr>
            <td>@lang('messages.expiration')</td>
            <td><span class="label label-success">{{ $token->expiration }} @lang('messages.time.hours', $token->expiration)</span></td>
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
            <td>@lang('messages.status')</td>
            <td><span class="label label-{{ $token->status()['class'] }}">{{ $token->status()['text'] }}</span></td>
        </tr>
        </tbody>
    </table>
    
    <p>
        <a class="btn btn-primary btn-block" href="{{ route('token-orders.create', ['token' => $token->token]) }}">Redeem Link</a>
        <a class="btn btn-success btn-lg btn-block clipboard-js" data-clipboard-text="{{ route('token-orders.create', $token->token) }}">Copy Link</a>
    </p>

@endsection