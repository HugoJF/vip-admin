@extends('layouts.app')

@section('content')
    <h1>Current Orders</h1>

    <p><a href="?trashed=true" id="generate" type="submit" name="generate" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span> Show trashed orders</a></p>

    <table id="datatables" class="table table-bordered {{ isset($highlight) ? '' : 'table-striped ' }}">
        <thead>
        <tr>
            <th>Order Public ID</th>
            @if($isAdmin)
            <th>Username</th>
            <th>Order Type</th>
            @endif
            <th>Duration</th>
            <th>Extra tokens</th>
            <th>State</th>
            <th>Actions</th>
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
                <td>{{ $order->duration }} {{ $order->duration == 1 ? 'day' : 'days' }}</td>

                <!-- Extra tokens -->
                <td>{{ $order->extra_tokens ?? '0' }} tokens</td>

                <!-- State -->
                <td><span class="label label-{{ $order->status()['class'] }}">{{ $order->status()['text'] }}</span></td>

                <!-- Actions -->
                <td style="white-space: nowrap;">
                    @if($order->orderable->status()['text'] != 'Confirmed')
                        <a class="btn btn-xs btn-primary" href="{{ route('orders.edit', $order) }}">Edit</a>
                    @endif
                    <a class="btn btn-xs btn-default" href="{{ route('orders.show', $order) }}">Order details</a>
                    @if($order->type('Steam') && !$order->orderable->tradeoffer_id && $isAdmin)
                        <a class="btn btn-xs btn-default" href="{{ route('steam-order.send-tradeoffer', $order) }}">Send Trade Offer</a>
                    @endif
                    @if($order->orderable->status()['text'] != 'Confirmed' && !$order->trashed())
                        {!! Form::open(['route' => ['orders.delete', $order], 'method' => 'DELETE', 'style' => 'display: inline;']) !!}
                            <button class="btn btn-xs btn-danger btn-form-fix" type="submit">Delete</button>
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