@extends('layouts.app')

@section('content')

    <h2>@lang('messages.token-form-confirmation')</h2>

    {!! Form::open(['route' => 'tokens.store', 'method' => 'POST']) !!}
    <table class="table table-hover">
        <tbody>
        <tr>
            <td>@lang('messages.duration')</td>
            <td><span class="label label-success">{{ $duration }} {{ strtolower(trans_choice('messages.time.days', 2)) }}</span></td>
            <input type="hidden" name="duration" value="{{ $duration }}">
        </tr>
        <tr>
            <td>@lang('messages.expiration')</td>
            <td><span class="label label-success">{{ $expiration }} {{ strtolower(trans_choice('messages.time.hours', 2)) }}</span></td>
            <input type="hidden" name="expiration" value="{{ $expiration }}">
        </tr>
        <tr>
            <td>@lang('messages.expiration-date')</td>
            <td><span class="label label-success">{{ $expiration_date }}</span></td>
        </tr>
        <tr>
            <td>@lang('note')</td>
            <td><span class="label label-success">{{ $note }}</span></td>
            <input type="hidden" name="note" value="{{ $note }}">
        </tr>
        </tbody>
    </table>
    <!-- Button -->
    <div class="form-group">
        <div class="col-md-12">
            <button id="generate" type="submit" name="generate" class="btn btn-block btn-success">@lang('messages.generate')</button>
        </div>
    </div>
    {!! Form::close() !!}
    
@endsection