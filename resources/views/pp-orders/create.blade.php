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

@section('content')<div class="jumbotron">
    <h1>Não sabe como funciona?</h1>
    <p>
        Nós desenvolvemos o VIP-Admin para que sua utilização seja <strong>a mais simples possível</strong>, mas é normal se sentir perdido ou confuso durante o proceso.
    </p>
    <p>
        Nós não temos um guia para compras pelo PayPal <strong>por causa da simplicidade do processo de pagamento</strong> e falta de necessidade para o mesmo.
    </p>
    <p>
        <strong>LEMBRETE</strong>: Em todos os casos de compra, <strong>é necessário ativar o seu VIP</strong> (gerar a confirmação do pedido). <a href="/orders">A página para gerar confirmação se encontra nos detalhes de qualquer pedido marcado como pago</a>.
    </p>
    <p>
        Se isso ainda não for o suficiente ou você tiver alguma dúvida ou problema, <strong>entre em contato conosco em nosso Discord ou comigo (de_nerd) pela Steam</strong>.
    </p>
    <p>
        <a class="btn btn-default btn-lg" href="https://denerdtv.com/discord" role="button">Discord</a>
        <a class="btn btn-default btn-lg" href="https://steamcommunity.com/id/de_nerd" role="button">Steam</a>
    </p>
</div>
    <h1>@lang('messages.pp-orders-create')</h1>
    {!! Form::open(['route' => 'pp-orders.store', 'method' => 'POST']) !!}
    
    <div class="row">
        @foreach(config('app.mp-periods') as $duration)
            <div class="col-sm-12 col-md-6">
                <div class="thumbnail">
                    <div class="caption">
                        
                        <h2 style="text-align: center">{{ $duration }} dias</h2>
                        
                        <h2 style="text-align: center"><strong>R$ {{ \App\Http\Controllers\MPOrderController::getPrice($duration) }}</strong></h2>
                        <div class="funkyradio">
                            <div class="funkyradio-success">
                                <input type="radio" name="duration" value="{{ $duration }}" id="checkbox-{{ $control }}"/>
                                <label id="checkbox-label-{{ $control }}" for="checkbox-{{ $control++ }}">@lang('messages.mp-order-select-duration')</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <button id="use-token" class="btn btn-block btn-lg btn-success" type="submit">@lang('messages.pp-order-create-submit')</button>
    
    {!! Form::close() !!}
@endsection