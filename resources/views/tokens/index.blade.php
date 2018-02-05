@extends('layouts.app')

@section('content')
    <h1>Current generated Tokens </h1>

    {!! Form::open(['route' => 'tokens.storeExtra', 'method' => 'POST', ]) !!}
    <p><button id="generate" type="submit" name="generate" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span> Generate extra tokens</a></button></p>
    {!! Form::close() !!}

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Token</th>
            <th>Duration</th>
            <th>Expiration</th>
            <th>Expiration remaining</th>
            <th>Redeem user</th>
            <th>Note</th>
            <th>Owner</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tokens as $token)
                <tr>
                    <td scope="row"><a href="{{ route('tokens.show', $token->token) }}">{{ $token->token}}</a></td>
                    <td>{{ $token->duration }} days</td>
                    <td>{{ $token->expiration }} hours</td>
                    @if($token->status() == 'Used')
                        <td>Already used</td>
                    @elseif($token->status() == 'Expired')
                        <td>Expired {{ $token->created_at->addHours($token->expiration)->diffForHumans() }}</td>
                    @else
                        <td>{{ $token->created_at->addHours($token->expiration)->diffForHumans() }}</td>
                    @endif
                    @if($token->tokenOrder && $token->tokenOrder->baseOrder)
                        <td><a href="http://steamcommunity.com/profiles/{{ $token->tokenOrder->baseOrder->user->steamid }}">{{ $token->tokenOrder->baseOrder->user->username }}</a></td>
                    @else
                        <td>N/A</td>
                    @endif
                        <td>{{ $token->note }}</td>
                    @if($token->user)
                        <td><a href="http://steamcommunity.com/profiles/{{ $token->user->steamid }}">{{ $token->user->username }}</a></td>
                    @else
                        <td>System</td>
                    @endif
                        <td><span class="label label-{{ $token->statusClass() }}">{{ $token->status() }}</span></td>
                    @if($token->tokenOrder && $token->tokenOrder->baseOrder)
                        <td><a class="btn btn-default" href="{{ route('token-order.show', $token->tokenOrder->baseOrder->public_id) }}">View order details</a></td>
                    @else
                        <td><a class="btn btn-default disabled">No actions available</a></td>
                    @endif
                </tr>
        @endforeach

        </tbody>
    </table>
@endsection