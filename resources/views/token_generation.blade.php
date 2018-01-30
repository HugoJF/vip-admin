@extends('layouts.app')

@section('content')


    {!! Form::open(['route' => 'token-generation', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
        <fieldset>

            <!-- Form Name -->
            <legend>Token generation form</legend>

            <!-- Select Basic -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="duration">Duration</label>
                <div class="col-md-4">
                    <select id="duration" name="duration" class="form-control">
                        <option value="-1">Custom</option>
                        <option value="1">1 day</option>
                        <option value="2">2 days</option>
                        <option value="3">3 days</option>
                        <option value="7">7 days</option>
                        <option value="14">14 days</option>
                        <option value="30">30 days</option>
                        <option value="60">60 days</option>
                        <option value="90">90 days</option>
                    </select>
                </div>
            </div>

            <!-- Appended Input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="custom-duration">Custom Duration</label>
                <div class="col-md-4">
                    <div class="input-group">
                        <input id="custom-duration" name="custom-duration" class="form-control" placeholder="3" type="text">
                        <span class="input-group-addon">days</span>
                    </div>
                    <p class="help-block">The amount of days the token will give</p>
                </div>
            </div>
            <!-- Select Basic -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="expiration">Expiration</label>
                <div class="col-md-4">
                    <select id="expiration" name="expiration" class="form-control">
                        <option value="-1">Custom</option>
                        <option value="1">1 hour</option>
                        <option value="2">2 hours</option>
                        <option value="4">4 hours</option>
                        <option value="12">12 hours</option>
                        <option value="24">1 day</option>
                        <option value="48">2 days</option>
                        <option value="168">7 days</option>
                        <option value="-1">Infinite</option>
                    </select>
                </div>
            </div>

            <!-- Appended Input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="custom-expiration">Custom Expiration</label>
                <div class="col-md-4">
                    <div class="input-group">
                        <input id="custom-expiration" name="custom-expiration" class="form-control" placeholder="12" type="text">
                        <span class="input-group-addon">hours</span>
                    </div>
                    <p class="help-block">How many hours the token will be valid</p>
                </div>
            </div>
            <!-- Textarea -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="note">Note</label>
                <div class="col-md-4">
                    <textarea class="form-control" id="note" name="note"></textarea>
                </div>
            </div>

            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="generate">Generate Token</label>
                <div class="col-md-4">
                    <button id="generate" type="submit" name="generate" class="btn btn-block btn-success">Generate</button>
                </div>
            </div>

        </fieldset>
    {!! Form::close() !!}



@endsection