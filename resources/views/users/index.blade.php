@extends('layouts.app')

@section('content')
    <h1>Current users</h1>

    <p><a href="?banned=true" id="generate" type="submit" name="generate" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span> Show banned users</a></p>

    <table id="datatables" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Order Count</th>
            <th>Confirmation Count</th>
            <th>Extra tokens</th>
            <th>Terms</th>
            <th>Trade Link</th>
            <th>Joined date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $key=>$user)
            <tr class="{{ $user->trashed() ? 'danger' : ''}}">
                <td data-order="{{ $key }}">{{ $user->name }}</td>
                <td><a href="http://steamcommunity.com/profiles/{{ $user->steamid }}">{{ $user->username }}</a></td>
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
                <td>
                    @if($user->trashed())
                        {!! Form::open(['route' => ['users.unban', $user], 'method' => 'PATCH']) !!}
                        <button id="ban" class="btn btn-primary" type="submit">Unban</button>
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['route' => ['users.ban', $user], 'method' => 'PATCH']) !!}
                        <button id="ban" class="btn btn-danger" type="submit">Ban</button>
                        {!! Form::close() !!}
                    @endif
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endsection

@push('scripts')
<script>

    $(document).ready(function(){
        $('#datatables').DataTable({
            "iDisplayLength": 50
        });
    });

</script>
@endpush