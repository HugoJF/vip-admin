@extends('layouts.app')

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <form action="/create-steam-offer">
            @foreach($inventory as $item)
                @if(array_key_exists($item->market_name, $prices))
                    <input type="checkbox" name="items[]" value="{{ json_encode([
                    'assetid' => $item->assetid,
                    'appid' => $item->appid,
                    'contextid' => $item->contextid,
                    'amount' => 1
                ]) }}">{{ $item->market_name }} - {{ $prices[$item->market_name] }}<br>
                @else
                    <input type="checkbox" disabled>{{ $item->market_name }} - NOT ACCEPTED<br>
                @endif
            @endforeach
            <input type="submit" value="Submit">
        </form>
    </div>
@endsection