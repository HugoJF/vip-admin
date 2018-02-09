@extends('layouts.app')

@section('content')
    <h1>Current users</h1>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Steam ID</th>
            <th>Order Count</th>
            <th>Confirmation Count</th>
            <th>Extra tokens</th>
            <th>Terms</th>
            <th>Trade Link</th>
            <th>Joined date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->username }}</td>
                <td><a href="http://steamcommunity.com/profiles/{{ $user->steamid }}">{{ $user->steamid }}</a></td>
                <td>{{ $user->orders()->count() }} orders</td>
                <td>{{ $user->confirmations()->count() }} confirmations</td>
                <td><span class="label label-default" >{{ $user->tokens()->count() }} / {{ $user->allowedTokens() }}</span></td>
                @if($user->accepted)
                    <td><span class="label label-success">Accepted</span></td>
                @else
                    <td><span class="label label-danger">Not accepted</span></td>
                @endif
                <td><a href="{{ $user->tradelink }}">Trade link</a></td>
                <td>{{ $user->created_at->diffForHumans() }}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endsection