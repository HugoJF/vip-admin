@extends('layouts.app')

@section('content')
    <h1>Current Orders</h1>
    
    @if(Auth::user()->isAdmin())
        <p>
            <a href="?trashed=true" id="generate" type="submit" name="generate" class="btn btn-default">
                <span class="glyphicon glyphicon-remove"></span> @lang('messages.order-show-trashed')
            </a>
        </p>
    @endif
    
    <table id="datatables" class="table table-bordered {{ isset($highlight) ? '' : 'table-striped ' }}">
        <thead>
        <tr>
            <th>@lang('messages.order-public-id')</th>
            @if($isAdmin)
                <th>@lang('messages.username')</th>
                <th>@lang('messages.order-type')</th>
            @endif
            <th>@lang('messages.duration')</th>
            <th>@lang('messages.extra-tokens')</th>
            <th>@lang('messages.created-at')</th>
            <th>@lang('messages.state')</th>
            <th>@lang('messages.actions')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $key=>$order)
            <tr {{ isset($highlight) && $order->user->steamid == $highlight ? 'class=info' : ($order->trashed() ? 'class=danger' : '') }}>
                <!-- Order Public ID -->
                <td data-order="{{ $key }}"><a href="{{ route('orders.show', $order) }}"><code>#{{ $order->public_id }}</code></a></td>
                
                <!-- Username and Order Type -->
                @if($isAdmin)
                    <td>
                        <a href="http://steamcommunity.com/profiles/{{ $order->user->steamid }}">{{ $order->user->username }}</a>
                        <a href="?highlight={{ $order->user->steamid }}"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
                    </td>
                    <td>{{ $order->orderable_type }}</td>
            @endif
            
            <!-- Duration -->
                <td>{{ $order->duration }} {{ strtolower(trans_choice('messages.time.days', $order->duration)) }}</td>
                
                <!-- Extra tokens -->
                <td>{{ $order->extra_tokens ?? '0' }} {{ strtolower(trans_choice('messages.token', $order->extra_tokens)) }}</td>
                
                <!-- Created At -->
                <td>{{  $order->created_at->diffForHumans() }}</td>
                
                <!-- State -->
                <td><span class="label label-{{ $order->status()['class'] }}">{{ $order->status()['text'] }}</span></td>
                
                <!-- Actions -->
                <td style="white-space: nowrap;">
                    
                    @if($order->orderable->status()['text'] != 'Confirmed')
                        <a class="btn btn-xs btn-primary" href="{{ route('orders.edit', $order) }}">@lang('messages.edit')</a>
                    @endif
                    @if($order->type('MercadoPago') && $isAdmin)
                        <a class="btn btn-xs btn-primary" href="{{ route('mp-orders.recheck', $order) }}">Recheck</a>
                    @endif
                    <a class="btn btn-xs btn-default" href="{{ route('orders.show', $order) }}">@lang('messages.order-details')</a>
                    @if($order->type('Steam') && !$order->orderable->tradeoffer_id && $isAdmin)
                        <a class="btn btn-xs btn-default" href="{{ route('steam-orders.send-tradeoffer-manual', $order) }}">@lang('messages.send-tradeoffer')</a>
                    @endif
                    @if($order->orderable->status()['text'] != 'Confirmed' && !$order->trashed())
                        {!! Form::open(['route' => ['orders.delete', $order], 'method' => 'DELETE', 'style' => 'display: inline;']) !!}
                        <button class="btn btn-xs btn-danger btn-form-fix" type="submit">@lang('messages.delete')</button>
                        {!! Form::close() !!}
                    @endif
                    @if($order->orderable->status()['text'] != 'Confirmed' && $order->trashed())
                        {!! Form::open(['route' => ['orders.restore', $order], 'method' => 'PATCH', 'style' => 'display: inline;']) !!}
                        <button class="btn btn-xs btn-success btn-form-fix" type="submit">@lang('messages.restore')</button>
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