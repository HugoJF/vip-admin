@extends('layouts.app')

@section('content')
    <h1>Current Confirmations</h1>

    <table class="table table-bordered {{ isset($highlight) ? '' : 'table-striped ' }}">
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
                <!-- Confirmation Public ID -->
                <td><a href="{{ route('steam-order.show', $confirmation->baseOrder->public_id) }}"><code>#{{ $confirmation->public_id }}</code></a></td>

                <!-- Order Public ID -->
                <td scope="row"><a href="{{ route('steam-order.show', $confirmation->baseOrder->public_id) }}"><code>#{{ $confirmation->baseOrder->public_id }}</code></a></td>

                <!-- Username -->
                @if($isAdmin)
                    <td>
                        <a href="http://steamcommunity.com/profiles/{{ $confirmation->user->steamid }}">{{ $confirmation->user->username }}</a>
                        <a href="?highlight={{ $confirmation->user->steamid }}" title="Highlight confirmations from {{ $confirmation->user->username }}"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
                    </td>
                @endif
                <!-- Starting Period -->
                <td>{{ $confirmation->start_period }}</td>

                <!-- Ending Period -->
                <td>{{ $confirmation->end_period }}</td>

                <!-- State -->
                <td><span class="label label-{{ $confirmation->stateClass() }}"> {{ $confirmation->stateText() }}</span></td>

                <!-- Actions -->
                <td>
                    <a class="btn btn-default" href="{{ route(($confirmation->baseOrder->isSteamOffer() ? 'steam' : 'token') . '-order.show', $confirmation->baseOrder->public_id) }}">View order</a>
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