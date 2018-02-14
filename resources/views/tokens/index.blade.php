@extends('layouts.app')

@section('content')
    <h1>Current generated Tokens </h1>

    {!! Form::open(['route' => 'tokens.storeExtra', 'method' => 'POST', ]) !!}
    <p><button id="generate" type="submit" name="generate" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span> Generate extra tokens</button></p>
    {!! Form::close() !!}
    <p><a href="?trashed=true" id="generate" type="submit" name="generate" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span> Show trashed confirmations</a></p>


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
            <tr {{ isset($token->tokenOrder->baseOrder->user->steamid) && $token->tokenOrder->baseOrder->user->steamid == $highlight || isset($token->user) && $token->user->steamid == $highlight ? 'class=info' : ($token->trashed() ? 'class=danger' : '') }}>
                    <!-- Token -->
                    <td scope="row"><a href="{{ route('tokens.show', $token) }}"><code>{{ $token->token}}</code></a></td>

                    <!-- Duration -->
                    <td>{{ $token->duration }} days</td>

                    <!-- Expiration -->
                    <td>{{ $token->expiration }} hours</td>

                    <!-- Expiration remaining -->
                    @if($token->status()['text'] == 'Used')
                        <td>Already used</td>
                    @elseif($token->status()['text'] == 'Expired')
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
                    <td><span class="label label-{{ $token->status['class'] }}">{{ $token->status()['text'] }}</span></td>

                    <!-- Actions -->
                    <td>
                        @if($token->tokenOrder && $token->tokenOrder->baseOrder)
                            <a class="btn btn-xs btn-default" href="{{ route('token-order.show', $token->tokenOrder->baseOrder->public_id) }}">View order details</a>
                        @else
                            <a class="btn btn-xs btn-primary" href="{{ route('tokens.edit', $token) }}">Edit</a>
                            <a class="btn btn-xs btn-default disabled">No actions available</a>
                        @endif
                        @if(Auth::user()->isAdmin())
                            @if($token->trashed())
                                {!! Form::open(['route' => ['tokens.restore', $token], 'method' => 'PATCH', 'style' => 'display: inline;']) !!}
                                <button class="btn btn-xs btn-primary">Restore</button>
                                {!! Form::close() !!}
                            @else
                                {!! Form::open(['route' => ['tokens.delete', $token], 'method' => 'DELETE', 'style' => 'display: inline;']) !!}
                                <button class="btn btn-xs btn-danger">Delete</button>
                                {!! Form::close() !!}
                            @endif
                        @endif
                    </td>
                </tr>
        @endforeach

        </tbody>
    </table>
@endsection