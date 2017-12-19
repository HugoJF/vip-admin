@extends('layouts.app')

@section('content')
    <h1> YO {{ Auth::user()->username }}</h1>
@endsection