@extends('layouts.app')

@section('content')
    <h1>User settings</h1>
    <br>

    {!! Form::model($user, ['route' => ['settings.update', $user]]) !!}

    <div class="form-group">
        {!! Form::label('name', 'Name') !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
        <p class="help-block">If you want us to use your real name put it here. This is only used for display on the dashboard and emails :)</p>
    </div>

    <div class="form-group">
        {!! Form::label('tradelink', 'Trade Link') !!}
        {!! Form::text('tradelink', null, ['class' => 'form-control']) !!}
        <p class="help-block">This is the link we will use to send trade offers. You can find your URL <a target="_blank" href="https://steamcommunity.com/id/me/tradeoffers/privacy#trade_offer_access_url">clicking here.</a></p>
    </div>

    <div class="form-group">
        {!! Form::label('email', 'Email') !!}
        {!! Form::email('email', null, ['class' => 'form-control']) !!}
        <p class="help-block">We will use this email to send notifications about everything related to your account and VIP-Admin. <strong>(recommended)</strong></p>
    </div>

    <button id="submit" class="btn btn-success" type="submit">Update</button>
    {!! Form::close() !!}
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#summernote_0').summernote();
    });
    $(document).ready(function() {
        $('#summernote_1').summernote();
    });
    $(document).ready(function() {
        $('#summernote_2').summernote();
    });
</script>
@endpush