@extends('layouts.app')

@section('content')
    <div class="jumbotron">
        <h1>Não sabe como funciona?</h1>
        <p>
            Nós desenvolvemos o VIP-Admin para que sua utilização seja <strong>a mais simples possível</strong>, mas é normal se sentir perdido ou confuso durante o proceso.
        </p>
        <p>
            Caso sinta necessidade ou tenha alguma, <strong>temos um guia que detalha todas as etapas do processo de compra com Tokens</strong>.
        </p>
        <p>
            Se isso ainda não for o suficiente ou você tiver alguma dúvida ou problema, <strong>entre em contato conosco em nosso Discord ou comigo (de_nerd) pela Steam</strong>.
        </p>
        
        <p>
            <a class="btn btn-primary btn-lg" href="https://denerdtv.com/como-comprar-vip-com-tokens/" role="button">Acessar guia de compra</a>
            <a class="btn btn-default btn-lg" href="https://denerdtv.com/discord" role="button">Discord</a>
            <a class="btn btn-default btn-lg" href="https://steamcommunity.com/id/de_nerd" role="button">Steam</a>
        </p>
    </div>
    <h1>@lang('messages.token-order')</h1>
    {!! Form::open(['route' => 'token-orders.create', 'method' => 'GET']) !!}
    
    <div class="form-group">
        {!! Form::label('token', 'Token') !!}
        {!! Form::text('token', null, ['class' => 'form-control']) !!}
    </div>
    <button id="use-token" class="btn btn-success" type="submit">@lang('messages.use-token')</button>
    
    {!! Form::close() !!}


@endsection