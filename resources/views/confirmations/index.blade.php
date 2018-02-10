@extends('layouts.app')

@section('content')
    <h1>Current Confirmations</h1>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Confirmation Public ID</th>
            <th>Order Public ID</th>
            @if($isAdmin)
                <th>Username</th>
            @endif
            <th>Starting Period</th>
            <th>Ending Period</th>
            <th>State</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($confirmations as $confirmation)
            <tr {{ isset($highlight) && $confirmation->user->steamid == $highlight ? 'class=info' : '' }}>
                <td>#{{ $confirmation->public_id }}</td>
                <td scope="row"><a href="{{ route('steam-order.show', $confirmation->baseOrder->public_id) }}">#{{ $confirmation->baseOrder->public_id }}</a></td>
                @if($isAdmin)
                    <td>
                        <a href="http://steamcommunity.com/profiles/{{ $confirmation->user->steamid }}">{{ $confirmation->user->username }}</a>
                        <a href="?highlight={{ $confirmation->user->steamid }}"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
                    </td>
                @endif
                <td>{{ $confirmation->start_period }}</td>
                <td>{{ $confirmation->end_period }}</td>
                <td><span class="label label-{{ $confirmation->stateClass() }}"> {{ $confirmation->stateText() }}</span></td>
                <td>
                    @if($confirmation->baseOrder->isSteamOffer())
                        <a class="btn btn-default" href="{{ route('steam-order.show', $confirmation->baseOrder->public_id) }}">View order</a>
                    @else
                        <a class="btn btn-default" href="{{ route('token-order.show', $confirmation->baseOrder->public_id) }}">View order</a>
                    @endif
                    @if(Auth::user()->isAdmin())
                        @if($confirmation->trashed())
                            {!! Form::open(['route' => ['confirmations.restore', $confirmation], 'method' => 'PUT']) !!}
                                 <button class="btn btn-primary">Restore</button>
                            {!! Form::close() !!}
                        @else
                            {!! Form::open(['route' => ['confirmations.destroy', $confirmation], 'method' => 'DELETE']) !!}
                                <button class="btn btn-danger">Delete</button>
                            {!! Form::close() !!}
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endsection