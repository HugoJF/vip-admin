@extends('layouts.app')

@section('content')
    <h1>Current Confirmations</h1>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Confirmation Public ID</th>
            <th>Order Public ID</th>
            <th>Username</th>
            <th>Starting Period</th>
            <th>Ending Period</th>
            <th>State</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($confirmations as $confirmation)
            <tr>
                <td>#{{ $confirmation->public_id }}</td>
                <td scope="row"><a href="{{ route('view-steam-order', $confirmation->baseOrder->public_id) }}">#{{ $confirmation->baseOrder->public_id }}</a></td>
                @if($isAdmin)
                    <td><a href="http://steamcommunity.com/profiles/{{ $confirmation->user->steamid }}">{{ $confirmation->user->username }}</a></td>
                @endif
                <td>{{ $confirmation->start_period }}</td>
                <td>{{ $confirmation->end_period }}</td>
                <td><span class="label label-{{ $confirmation->stateClass() }}"> {{ $confirmation->stateText() }}</span></td>
                @if($confirmation->baseOrder->isSteamOffer())
                    <td><a class="btn btn-default" href="{{ route('view-steam-order', $confirmation->baseOrder->public_id) }}">View order</a></td>
                @else
                    <td><a class="btn btn-default" href="{{ route('view-token-order', $confirmation->baseOrder->public_id) }}">View order</a></td>
                @endif
            </tr>
        @endforeach

        </tbody>
    </table>
@endsection