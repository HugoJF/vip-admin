@extends('layouts.app')

@section('content')
    <div class="jumbotron">
        <h1> Welcome, {{ Auth::user()->name ? Auth::user()->name : Auth::user()->username }}</h1>
    </div>
@endsection