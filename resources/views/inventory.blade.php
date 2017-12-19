@extends('layouts.app')

@section('content')
    <form action="/create-steam-offer">
        @foreach($inventory as $item)
            @if(array_key_exists($item->market_name, $prices))
                <input type="checkbox" name="items[]" value="{{ json_encode([
                    'assetid' => $item->assetid,
                    'appid' => $item->appid,
                    'contextid' => $item->contextid,
                    'amount' => 1
                ]) }}">{{ $item->market_name }} - ${{ round(($prices[$item->market_name] / 100), 3) }}<br>
            @else
                <input type="checkbox" disabled>{{ $item->market_name }} - NOT ACCEPTED<br>
            @endif
        @endforeach
        <input type="submit" value="Submit">
    </form>
@endsection