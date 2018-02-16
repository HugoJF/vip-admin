@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <h1>@lang('messages.confirmation-form')</h1>
            <br>

            {!! form_start($form) !!}

            {!! form_rest($form) !!}

            <div class="form-footer">
                <button type="submit" class="btn-success btn">@lang('messages.save')</button>
            </div>

            {!! form_end($form) !!}
        </div>
    </div>
@endsection