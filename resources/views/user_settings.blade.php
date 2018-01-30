@extends('layouts.app')

@section('content')
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

    <button class="btn btn-success" type="submit">Update</button>
    {!! Form::close() !!}
@endsection