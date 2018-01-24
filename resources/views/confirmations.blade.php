@extends('layouts.app')

@section('content')
    <h1>Current Confirmations</h1>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Order Public ID</th>
            <th>Starting Period</th>
            <th>Ending Period</th>
            <th>State</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($confirmations as $confirmation)
            <tr>
                <td scope="row"><a href="{{ route('view-steam-order', $confirmation->order->public_id) }}">#{{ $confirmation->order->public_id }}</a></td>
                <td>{{ $confirmation->start_period }}</td>
                <td>{{ $confirmation->end_period }}</td>
                <td><span class="label label-{{ $confirmation->isValid() ? 'success' : 'danger' }}"> {{ $confirmation->isValid() ? 'Valid' : 'Expired' }}</span></td>
                <td><a class="btn btn-default" href="{{ route('view-steam-order', $confirmation->order->public_id) }}">View order</a></td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endsection