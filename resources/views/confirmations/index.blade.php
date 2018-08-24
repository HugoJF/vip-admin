@extends('layouts.app')

@section('content')
    <h1>Current Confirmations</h1>
    
    @if(Auth::user()->isAdmin())
        <p>
            <a href="?trashed=true" id="generate" type="submit" name="generate" class="btn btn-default">
                <span class="glyphicon glyphicon-remove"></span> @lang('messages.confirmation-show-trashed')
            </a>
        </p>
    @endif
    <table id="datatables" class="table table-bordered {{ isset($highlight) ? '' : 'table-striped ' }}">
        <thead>
        <tr>
            <th>@lang('messages.confirmation-public-id ')</th>
            <th>@lang('messages.order-public-id')</th>
            @if($isAdmin)
                <th>@lang('messages.username')</th>
            @endif
            <th>@lang('messages.created-at')</th>
            <th>@lang('messages.confirmation-ending-period')</th>
            <th>@lang('messages.state')</th>
            <th>@lang('messages.actions')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($confirmations as $key=>$confirmation)
            <tr {{ isset($highlight) && $confirmation->user->steamid == $highlight ? 'class=info' : $confirmation->trashed() ? 'class=danger' : '' }}>
                <!-- Confirmation Public ID -->
                <td data-order="{{ $key }}"><a href="{{ route('orders.show', $confirmation->baseOrder) }}"><code>#{{ $confirmation->public_id }}</code></a></td>
                
                <!-- Order Public ID -->
                <td scope="row"><a href="{{ route('orders.show', $confirmation->baseOrder) }}"><code>#{{ $confirmation->baseOrder->public_id }}</code></a></td>
                
                <!-- Username -->
                @if($isAdmin)
                    <td>
                        <a href="http://steamcommunity.com/profiles/{{ $confirmation->user->steamid }}">{{ $confirmation->user->username }}</a>
                        <a href="?highlight={{ $confirmation->user->steamid }}" title="@lang('messages.confirmation-highlight-from', ['user' => $confirmation->user->username])"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
                    </td>
                @endif
                <!-- Created At -->
                <td>{{ $confirmation->created_at->diffToHumans() }}</td>
                
                <!-- Ending Period -->
                <td>{{ $confirmation->end_period }}</td>
                
                <!-- State -->
                <td><span class="label label-{{ $confirmation->status()['class'] }}"> {{ $confirmation->status()['text'] }}</span></td>
                
                <!-- Actions -->
                <td style="white-space: nowrap;">
                    <a class="btn btn-xs btn-default" href="{{ route('orders.show', $confirmation->baseOrder) }}">@lang('messages.view-order')</a>
                    <a class="btn btn-xs btn-primary" href="{{ route('confirmations.edit', $confirmation) }}">@lang('messages.edit')</a>
                    @if(Auth::user()->isAdmin())
                        @if($confirmation->trashed())
                            {!! Form::open(['route' => ['confirmations.restore', $confirmation], 'method' => 'PATCH', 'style' => 'display: inline;']) !!}
                            <button class="btn btn-xs btn-primary">@lang('messages.restore')</button>
                            {!! Form::close() !!}
                        @else
                            {!! Form::open(['route' => ['confirmations.delete', $confirmation], 'method' => 'DELETE', 'style' => 'display: inline;']) !!}
                            <button class="btn btn-xs btn-danger">@lang('messages.delete')</button>
                            {!! Form::close() !!}
                        @endif
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