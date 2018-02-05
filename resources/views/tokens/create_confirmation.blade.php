@extends('layouts.app')

@section('content')

    <h2>Token generation confirmation details</h2>

    {!! Form::open(['route' => 'tokens.store', 'method' => 'POST']) !!}
    <table class="table table-hover">
        <tbody>
        <tr>
            <td>Duration</td>
            <td><span class="label label-success">{{ $duration }} days</span></td>
            <input type="hidden" name="duration" value="{{ $duration }}">
        </tr>
        <tr>
            <td>Expiration</td>
            <td><span class="label label-success">{{ $expiration }} hours</span></td>
            <input type="hidden" name="expiration" value="{{ $expiration }}">
        </tr>
        <tr>
            <td>Expiration Date</td>
            <td><span class="label label-success">{{ $expiration_date }}</span></td>
        </tr>
        <tr>
            <td>Note</td>
            <td><span class="label label-success">{{ $note }}</span></td>
            <input type="hidden" name="note" value="{{ $note }}">
        </tr>
        </tbody>
    </table>
    <!-- Button -->
    <div class="form-group">
        <div class="col-md-12">
            <button id="generate" type="submit" name="generate" class="btn btn-block btn-success">Generate</button>
        </div>
    </div>
    {!! Form::close() !!}



@endsection