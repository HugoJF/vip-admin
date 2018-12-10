@extends('layouts.app')

@section('content')
    <h1>{{ $title }}</h1>
    <pre class="prettyprint linenums">{{ $content }}</pre>
@endsection

@push('scripts')
    <script>
        var html = $('.prettyprint').html();
        var nice = JSON.stringify(JSON.parse(html), null, 4);
        $('.prettyprint').html(nice);
    </script>
    
    <script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
@endpush