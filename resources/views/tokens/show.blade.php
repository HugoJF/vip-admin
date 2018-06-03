@extends('layouts.app')

@section('content')
    
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
            <td><span class="label label-success">{{ $token->expiration }} hours</span></td>
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
            <td><span class="label label-{{ $token->status()['class'] }}">{{ $token->status()['text'] }}</span></td>
        </tr>
        </tbody>
    </table>
    
    <p>
        <a class="btn btn-primary btn-block" href="{{ route('tokens.show', $token->token) }}">Redeem Link</a>
        <a class="btn btn-success btn-lg btn-block clipboard-js" data-clipboard-text="{{ route('tokens.show', $token->token) }}">Copy Link</a>
    </p>

@endsection