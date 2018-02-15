@extends('layouts.app')

@section('content')

@if(!isset($confirming) || $confirming == false)
    {!! Form::open(['route' => 'tokens.create', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
        <fieldset>

            <!-- Form Name -->
            <legend>Token generation form</legend>

            <!-- Select Basic -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="duration">Duration</label>
                <div class="col-md-4">
                    <select id="duration" name="duration" class="form-control">
                        <option {{ old('duration') == -1 ? 'selected' : '' }} value="-1">Custom</option>
                        <option {{ old('duration') == 1 ? 'selected' : '' }} value="1">1 day</option>
                        <option {{ old('duration') == 2 ? 'selected' : '' }} value="2">2 days</option>
                        <option {{ old('duration') == 3 ? 'selected' : '' }} value="3">3 days</option>
                        <option {{ old('duration') == 7 ? 'selected' : '' }} value="7">7 days</option>
                        <option {{ old('duration') == 14 ? 'selected' : '' }} value="14">14 days</option>
                        <option {{ old('duration') == 30 ? 'selected' : '' }} value="30">30 days</option>
                        <option {{ old('duration') == 60 ? 'selected' : '' }} value="60">60 days</option>
                        <option {{ old('duration') == 90 ? 'selected' : '' }} value="90">90 days</option>
                        <option {{ old('duration') == 180 ? 'selected' : '' }} value="180">180 days</option>
                    </select>
                </div>
            </div>

            <!-- Appended Input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="custom-duration">Custom Duration</label>
                <div class="col-md-4">
                    <div class="input-group">
                        <input value="{{ old('custom-duration') }}" id="custom-duration" name="custom-duration" class="form-control" placeholder="3" type="text">
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
                        <option {{ old('expiration') == -1 ? 'selected' : '' }} value="-1">Custom</option>
                        <option {{ old('expiration') == 1 ? 'selected' : '' }}  value="1">1 hour</option>
                        <option {{ old('expiration') == 2 ? 'selected' : '' }} value="2">2 hours</option>
                        <option {{ old('expiration') == 4 ? 'selected' : '' }} value="4">4 hours</option>
                        <option {{ old('expiration') == 12 ? 'selected' : '' }} value="12">12 hours</option>
                        <option {{ old('expiration') == 24 ? 'selected' : '' }} value="24">1 day</option>
                        <option {{ old('expiration') == 48 ? 'selected' : '' }} value="48">2 days</option>
                        <option {{ old('expiration') == 168 ? 'selected' : '' }} value="168">7 days</option>
                        <option {{ old('expiration') === 0 ? 'selected' : '' }} value="0">Infinite</option>
                    </select>
                </div>
            </div>

            <!-- Appended Input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="custom-expiration">Custom Expiration</label>
                <div class="col-md-4">
                    <div class="input-group">
                        <input value="{{ old('custom-expiration') }}" id="custom-expiration" name="custom-expiration" class="form-control" placeholder="12" type="text">
                        <span class="input-group-addon">hours</span>
                    </div>
                    <p class="help-block">How many hours the token will be valid</p>
                </div>
            </div>
            <!-- Textarea -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="note">Note</label>
                <div class="col-md-4">
                    <textarea class="form-control" id="note" name="note">{{ old('note') }}</textarea>
                </div>
            </div>

            <input type="hidden" name="confirming" value="true">

            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="generate">Generate Token</label>
                <div class="col-md-4">
                    <button id="generate" type="submit" name="generate" class="btn btn-block btn-success">Generate</button>
                </div>
            </div>

        </fieldset>
    {!! Form::close() !!}
@else
    {!! Form::open(['route' => 'token-generate', 'method' => 'POST']) !!}
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

    <input type="hidden" name="confirmed" value="true">

    <!-- Button -->
    <div class="form-group">
        <div class="col-md-12">
            <button id="generate" type="submit" name="generate" class="btn btn-block btn-success">Generate</button>
        </div>
    </div>
    {!! Form::close() !!}
@endif


@endsection