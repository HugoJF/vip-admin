@extends('layouts.app')

@section('content')
    <h1>Current servers</h1>

    <p><a href="{{ route('servers.create') }}" id="generate" type="submit" name="generate" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span> Add new server</a></p>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Server name</th>
            <th>Server IP</th>
            <th>Server Port</th>
            <th>Server Password</th>
            <th>Date Updated</th>
            <th>Date Synced</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($servers as $server)
            <tr>
                <td>{{ $server->name }}</td>
                <td>{{ $server->ip }}</td>
                <td>{{ $server->port }}</td>
                <td>{{ $server->password }}</td>
                <td>{{ $server->updated_at->diffForHumans() }}</td>
                <td>{{ $server->synced_at ? $server->synced_at->diffForHumans() : 'Never' }}</td>
                <td>
                    <a href="{{ route('servers.edit', $server) }}" class="btn btn-primary">Edit</a>
                    {{ Form::open(['route' => ['servers.delete', $server], 'method' => 'DELETE']) }}
                        <button class="btn btn-danger">Delete</button>
                    {{ Form::close() }}
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endsection