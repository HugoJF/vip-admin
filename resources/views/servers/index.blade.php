@extends('layouts.app')

@section('content')
    <h1>Current servers</h1>
    <p><a href="{{ route('servers.create') }}" id="generate" type="submit" name="generate" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span> Add new server</a></p>

    <table id="datatables" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>@lang('messages.server-name')</th>
            <th>@lang('messages.server-ip')</th>
            <th>@lang('messages.server-port')</th>
            <th>@lang('messages.server-password')</th>
            <th>@lang('messages.server-ftp-host')</th>
            <th>@lang('messages.server-ftp-user')</th>
            <th>@lang('messages.server-ftp-password')</th>
            <th>@lang('messages.server-ftp-root')</th>
            <th>@lang('messages.last-update')</th>
            <th>@lang('messages.last-sync')</th>
            <th>@lang('messages.actions')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($servers as $key=>$server)
            <tr>
                <td data-order="{{ $key }}">{{ $server->name }}</td>
                <td>{{ $server->ip }}</td>
                <td>{{ $server->port }}</td>
                <td>{{ $server->password }}</td>
                <td>{{ $server->ftp_host }}</td>
                <td>{{ $server->ftp_user }}</td>
                <td>{{ $server->ftp_password }}</td>
                <td>{{ $server->ftp_root }}</td>
                <td>{{ $server->updated_at->diffForHumans() }}</td>
                <td>{{ $server->synced_at ? $server->synced_at->diffForHumans() : trans('messages.never') }}</td>
                <td style="white-space: nowrap;">
                    <a href="{{ route('servers.edit', $server) }}" class="btn btn-xs btn-primary">@lang('messages.edit')</a>
                    {!! Form::open(['route' => ['servers.delete', $server], 'method' => 'DELETE', 'style' => 'display: inline;']) !!}
                        <button class="btn btn-xs btn-danger">@lang('messages.delete')</button>
                    {!! Form::close() !!}
                    {!! Form::open(['route' => ['servers.sync', $server], 'method' => 'POST', 'style' => 'display: inline;']) !!}
                    <button class="btn btn-xs btn-success">@lang('messages.sync')</button>
                    {!! Form::close() !!}
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