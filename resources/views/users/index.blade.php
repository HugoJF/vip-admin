@extends('layouts.app')

@section('content')
    <h1>Current users</h1>
    <p><a href="?banned=true" id="generate" type="submit" name="generate" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span> Show banned users</a></p>
    
    <table id="datatables" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>@lang('messages.name')</th>
            <th>@lang('messages.username')</th>
            <th>@lang('messages.order-count')</th>
            <th>@lang('messages.confirmation-count')</th>
            <th>@lang('messages.extra-tokens')</th>
            <th>@lang('messages.terms')</th>
            <th>@lang('messages.trade-link')</th>
            <th>@lang('messages.email')</th>
            <th>@lang('messages.joined-date')</th>
            <th>@lang('messages.actions')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $key=>$user)
            <tr class="{{ $user->trashed() ? 'danger' : ''}}">
                <!-- Name -->
                <td data-order="{{ $key }}">{{ $user->name }}</td>
    
                <!-- Username -->
                <td><a href="http://steamcommunity.com/profiles/{{ $user->steamid }}">{{ $user->username }}</a></td>
    
                <!-- Order Count -->
                <td>{{ $user->orders()->count() }} {{ strtolower(trans_choice('messages.order', 2)) }}</td>
    
                <!-- Confirmation Count -->
                <td>{{ $user->confirmations()->count() }} {{ strtolower(trans_choice('messages.confirmation', 2)) }}</td>
    
                <!-- Extra token count-->
                <td><span class="label label-default">{{ $user->tokens()->count() }} / {{ $user->allowedTokens() }}</span></td>
                
                <!-- Terms -->
                @if($user->accepted)
                    <td><span class="label label-success">@lang('messages.accepted')</span></td>
                @else
                    <td><span class="label label-danger">@lang('messages.not-accepted')</span></td>
                @endif
                
                <!-- Tradelink -->
                <td><a href="{{ $user->tradelink }}">@lang('messages.trade-link')</a></td>
                
                <!-- Email -->
                <td>{{ $user->email ?? 'N/A' }}</td>
                
                <!-- Join date -->
                <td>{{ $user->created_at->diffForHumans() }}</td>
                
                <!-- Actions -->
                <td style="white-space: nowrap;">
                    @if($user->trashed())
                        {!! Form::open(['route' => ['users.unban', $user], 'method' => 'PATCH']) !!}
                        <button id="ban" class="btn btn-primary" type="submit">@lang('messages.unban')</button>
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['route' => ['users.ban', $user], 'method' => 'PATCH']) !!}
                        <button id="ban" class="btn btn-danger" type="submit">@lang('messages.ban')</button>
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

        $(document).ready(function () {
            $('#datatables').DataTable({
                "iDisplayLength": 50
            });
        });
    
    </script>
@endpush