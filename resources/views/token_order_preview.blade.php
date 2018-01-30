@extends('layouts.app')

@section('content')
    {!! Form::open(['route' => 'create-token-order', 'method' => 'POST']) !!}

    <input type="hidden" name="token" value="{{ $token->token }}">
    <h2>Viewing token: <strong>{{ $token->token }}</strong></h2>

    <table class="table table-hover">
        <tbody>
        <tr>
            <td>Token</td>
            <td><span class="label label-success">{{ $token->token}}</span></td>
        </tr>
        <tr>
            <td>Duration</td>
            <td><span class="label label-success">{{ $token->duration }} days</span></td>
        </tr>
        <tr>
            <td>Expiration</td>
            <td><span class="label label-success">${{ $token->expiration }} hours</span></td>
        </tr>
        <tr>
            <td>Expiration Date</td>
            <td><span class="label label-success">{{ \Carbon\Carbon::now()->addHours($token->expiration) }}</span></td>
        </tr>
        <tr>
            <td>Expiration Remaining</td>
            <td><span class="label label-success">{{ \Carbon\Carbon::now()->addHours($token->expiration)->diffForHumans() }}</span></td>
        </tr>
        <tr>
            <td>Note</td>
            <td><span class="label label-success">{{ $token->note }}</span></td>
        </tr>
        <tr>
            <td>Status</td>
            <td><span class="label label-{{ $token->statusClass() }}">{{ $token->status() }}</span></td>
        </tr>
        </tbody>
    </table>

    <button class="btn btn-success btn-block" type="submit">Confirm token</button>

    {!! Form::close() !!}


@endsection