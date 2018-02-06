@extends('layouts.app')

@section('head')
    <style>
        .funkyradio div {
            clear: both;
            overflow: hidden;
        }

        .funkyradio label {
            width: 100%;
            border-radius: 3px;
            border: 1px solid #D1D3D4;
            font-weight: normal;
        }

        .funkyradio input[type="radio"]:empty,
        .funkyradio input[type="checkbox"]:empty {
            display: none;
        }

        .funkyradio input[type="radio"]:empty ~ label,
        .funkyradio input[type="checkbox"]:empty ~ label {
            position: relative;
            line-height: 2.5em;
            text-indent: 3.25em;
            margin-top: 2em;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .funkyradio input[type="radio"]:empty ~ label:before,
        .funkyradio input[type="checkbox"]:empty ~ label:before {
            position: absolute;
            display: block;
            top: 0;
            bottom: 0;
            left: 0;
            content: '';
            width: 2.5em;
            background: #D1D3D4;
            border-radius: 3px 0 0 3px;
        }

        .funkyradio input[type="radio"]:hover:not(:checked) ~ label,
        .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label {
            color: #888;
        }

        .funkyradio input[type="radio"]:hover:not(:checked) ~ label:before,
        .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label:before {
            content: '\2714';
            text-indent: .9em;
            color: #C2C2C2;
        }

        .funkyradio input[type="radio"]:checked ~ label,
        .funkyradio input[type="checkbox"]:checked ~ label {
            color: #777;
        }

        .funkyradio-success input[type="radio"]:checked ~ label,
        .funkyradio-success input[type="checkbox"]:checked ~ label {
            border: 1px solid #5cb85c;
            color: #5cb85c;
        }

        .funkyradio input[type="radio"]:checked ~ label:before,
        .funkyradio input[type="checkbox"]:checked ~ label:before {
            content: '\2714';
            text-indent: .9em;
            color: #333;
            background-color: #ccc;
        }

        .funkyradio input[type="radio"]:focus ~ label:before,
        .funkyradio input[type="checkbox"]:focus ~ label:before {
            box-shadow: 0 0 0 3px #999;
        }

        .funkyradio-default input[type="radio"]:checked ~ label:before,
        .funkyradio-default input[type="checkbox"]:checked ~ label:before {
            color: #333;
            background-color: #ccc;
        }

        .funkyradio-primary input[type="radio"]:checked ~ label:before,
        .funkyradio-primary input[type="checkbox"]:checked ~ label:before {
            color: #fff;
            background-color: #337ab7;
        }

        .funkyradio-success input[type="radio"]:checked ~ label:before,
        .funkyradio-success input[type="checkbox"]:checked ~ label:before {
            color: #fff;
            background-color: #5cb85c;
        }

        .funkyradio-danger input[type="radio"]:checked ~ label:before,
        .funkyradio-danger input[type="checkbox"]:checked ~ label:before {
            color: #fff;
            background-color: #d9534f;
        }

        .funkyradio-warning input[type="radio"]:checked ~ label:before,
        .funkyradio-warning input[type="checkbox"]:checked ~ label:before {
            color: #fff;
            background-color: #f0ad4e;
        }

        .funkyradio-info input[type="radio"]:checked ~ label:before,
        .funkyradio-info input[type="checkbox"]:checked ~ label:before {
            color: #fff;
            background-color: #5bc0de;
        }
    </style>
@endsection

@php
$control = 1;
@endphp

@section('content')
    <h1>Select your items you want to trade</h1>

    <form action="{{ route('steam-order.store') }}" method="POST">
        <div class="row">
            @foreach($inventory as $key=>$item)
                @if(array_key_exists($item->market_name, $prices))
                    @php
                    $price = round(($prices[$item->market_name] / 100), 3);
                    $value = json_encode([
                        'assetid' => $item->assetid,
                        'appid' => $item->appid,
                        'contextid' => $item->contextid,
                        'amount' => 1,
                        'price' => $price,
                    ])
                    @endphp
                    <div class="col-sm-6 col-md-2">
                        <div class="thumbnail">
                            <img width="200px" src="https://steamcommunity-a.akamaihd.net/economy/image/{{ $item->icon_url }}" alt="...">

                            <div class="caption">

                                <h4>{{ $item->market_name }}</h4>

                                <h3><strong>${{ $price }}</strong></h3>
                                <div class="funkyradio">
                                    <div class="funkyradio-success">
                                        <input type="checkbox" name="items[]" value="{{ $value }}" id="checkbox-{{ $control }}"/>
                                        <label for="checkbox-{{ $control }}">Use this item on trade</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(($control) % 6 == 0)
                        <div class="clearfix visible-lg-block visible-md-block"></div>
                    @endif
                    @if(($control++) % 2 == 0)
                        <div class="clearfix visible-sm-block"></div>
                    @endif
                @endif
            @endforeach
        </div>
        <button id="submit-items" type="submit" class="btn btn-success btn-lg btn-block">Submit items to trade</button>
    </form>
@endsection

@section('navbar')
    <li>
        <p style="padding: 0px 15px;" class="navbar-btn">
            <a href="#submit-items" class="btn btn-success">Finish selection!</a>
        </p>
    </li>
    <li><a id="totalPrice"><span class="label label-primary"><u>Total Price: $ 0</u></span></a></li>
    <li><a id="totalDays"><span class="label label-primary"><u>Total days: 0 days</u></span></a></li>
@endsection

@push('scripts')
    <script>

        function updateNumbers()
        {
                var price = 0;

                $('.funkyradio > .funkyradio-success > input:checked').each(function (id, elem) {
                    price += JSON.parse(elem.value).price;
                });

                var days = Math.floor(price / {{ Setting::get('cost-per-day') }} * 100 );
                price = Math.floor(price * 100) / 100;

                $('#totalPrice > span > u').text('Total Price: $ ' + price)
                $('#totalDays > span > u').text('Total Days: ' + days + ' days');
        }

        $(function() {
            $(".funkyradio > .funkyradio-success > input").change(function() {
                updateNumbers();
            });

            updateNumbers();
        });
    </script>
@endpush