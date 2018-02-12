@extends('layouts.app')

@section('content')
    <h1>Current generated Tokens </h1>

    {!! Form::open(['route' => 'tokens.storeExtra', 'method' => 'POST', ]) !!}
    <p><button id="generate" type="submit" name="generate" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span> Generate extra tokens</button></p>
    {!! Form::close() !!}

    <table class="table table-bordered {{ isset($highlight) ? '' : 'table-striped ' }}">
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
            <tr {{ isset($token->tokenOrder->baseOrder->user->steamid) && $token->tokenOrder->baseOrder->user->steamid == $highlight || isset($token->user) && $token->user->steamid == $highlight ? 'class=info' : '' }}>
                    <!-- Token -->
                    <td scope="row"><a href="{{ route('tokens.show', $token->token) }}"><code>{{ $token->token}}</code></a></td>

                    <!-- Duration -->
                    <td>{{ $token->duration }} days</td>

                    <!-- Expiration -->
                    <td>{{ $token->expiration }} hours</td>

                    <!-- Expiration remaining -->
                    @if($token->status() == 'Used')
                        <td>Already used</td>
                    @elseif($token->status() == 'Expired')
                        <td>Expired {{ $token->created_at->addHours($token->expiration)->diffForHumans() }}</td>
                    @else
                        <td>{{ $token->created_at->addHours($token->expiration)->diffForHumans() }}</td>
                    @endif

                    <!-- Redeem User -->
                    @if($token->tokenOrder && $token->tokenOrder->baseOrder)
                        <td>
                            <a href="http://steamcommunity.com/profiles/{{ $token->tokenOrder->baseOrder->user->steamid }}">{{ $token->tokenOrder->baseOrder->user->username }}</a>
                            <a href="?highlight={{ $token->tokenOrder->baseOrder->user->steamid }}" title="Highlight confirmations from {{ $token->tokenOrder->baseOrder->user->username }}"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
                        </td>
                    @else
                        <td>N/A</td>
                    @endif

                    <!-- Note -->
                    <td>{{ $token->note }}</td>

                    <!-- Owner -->
                    @if($token->user)
                        <td>
                            <a href="http://steamcommunity.com/profiles/{{ $token->user->steamid }}">{{ $token->user->username }}</a>
                            <a href="?highlight={{ $token->user->steamid }}" title="Highlight confirmations from {{ $token->user->username }}"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
                        </td>
                    @else
                        <td>System</td>
                    @endif

                    <!-- Status -->
                    <td><span class="label label-{{ $token->statusClass() }}">{{ $token->status() }}</span></td>

                    <!-- Actions -->
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