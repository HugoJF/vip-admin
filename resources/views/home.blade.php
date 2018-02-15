@extends('layouts.app')

@section('content')
    <div class="jumbotron">
        <h1>Bem vindo, {{ Auth::user()->name ? Auth::user()->name : Auth::user()->username }}</h1>

        {!! Setting::get('global-home') !!}

        @if(!Auth::user()->accepted)
            {!! Setting::get('not-accepted-home') !!}
            <a href="{{ route('users.accept') }}" class="btn btn-lg btn-primary btn-block">Estou ciente de todas as observações feitas acima</a>
        @else
            {!! Setting::get('accepted-home') !!}
        @endif
    </div>
@endsection