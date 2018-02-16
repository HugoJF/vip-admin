@extends('layouts.app')

@section('content')
    
    @if(!isset($confirming) || $confirming == false)
        {!! Form::open(['route' => 'tokens.create', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
        <fieldset>
            
            <!-- Form Name -->
            <legend>@lang('messages.token-form')</legend>
            
            <!-- Select Basic -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="duration">@lang('messages.duration')</label>
                <div class="col-md-4">
                    <select id="duration" name="duration" class="form-control">
                        <option {{ old('duration') == -1 ? 'selected' : '' }} value="-1">@lang('messages.custom')</option>
                        <option {{ old('duration') == 1 ? 'selected' : '' }} value="1">1 {{ strtolower(trans_choice('messages.time.days', 1)) }}</option>
                        <option {{ old('duration') == 2 ? 'selected' : '' }} value="2">2 {{ strtolower(trans_choice('messages.time.days', 2)) }}</option>
                        <option {{ old('duration') == 3 ? 'selected' : '' }} value="3">3 {{ strtolower(trans_choice('messages.time.days', 3)) }}</option>
                        <option {{ old('duration') == 7 ? 'selected' : '' }} value="7">7 {{ strtolower(trans_choice('messages.time.days', 7)) }}</option>
                        <option {{ old('duration') == 14 ? 'selected' : '' }} value="14">14 {{ strtolower(trans_choice('messages.time.days', 14)) }}</option>
                        <option {{ old('duration') == 30 ? 'selected' : '' }} value="30">30 {{ strtolower(trans_choice('messages.time.days', 30)) }}</option>
                        <option {{ old('duration') == 60 ? 'selected' : '' }} value="60">60 {{ strtolower(trans_choice('messages.time.days', 60)) }}</option>
                        <option {{ old('duration') == 90 ? 'selected' : '' }} value="90">90 {{ strtolower(trans_choice('messages.time.days', 90)) }}</option>
                        <option {{ old('duration') == 180 ? 'selected' : '' }} value="180">180 {{ strtolower(trans_choice('messages.time.days', 180)) }}</option>
                    </select>
                </div>
            </div>
            
            <!-- Appended Input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="custom-duration">@lang('messages.custom-duration')</label>
                <div class="col-md-4">
                    <div class="input-group">
                        <input value="{{ old('custom-duration') }}" id="custom-duration" name="custom-duration" class="form-control" placeholder="3" type="text">
                        <span class="input-group-addon">{{ strtolower(trans_choice('messages.time.days', 2)) }}</span>
                    </div>
                    <p class="help-block">@lang('messages.token-custom-duration-help')</p>
                </div>
            </div>
            <!-- Select Basic -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="expiration">@lang('messages.expiration')</label>
                <div class="col-md-4">
                    <select id="expiration" name="expiration" class="form-control">
                        <option {{ old('expiration') == -1 ? 'selected' : '' }} value="-1">@lang('messages.custom')</option>
                        <option {{ old('expiration') == 1 ? 'selected' : '' }}  value="1">1 {{ strtolower(trans_choice('messages.time.hours', 1)) }}</option>
                        <option {{ old('expiration') == 2 ? 'selected' : '' }} value="2">2 {{ strtolower(trans_choice('messages.time.hours', 2)) }}</option>
                        <option {{ old('expiration') == 4 ? 'selected' : '' }} value="4">4 {{ strtolower(trans_choice('messages.time.hours', 4)) }}</option>
                        <option {{ old('expiration') == 12 ? 'selected' : '' }} value="12">12 {{ strtolower(trans_choice('messages.time.hours', 12)) }}</option>
                        <option {{ old('expiration') == 24 ? 'selected' : '' }} value="24">1 {{ strtolower(trans_choice('messages.time.days', 1)) }}</option>
                        <option {{ old('expiration') == 48 ? 'selected' : '' }} value="48">2 {{ strtolower(trans_choice('messages.time.days', 2)) }}</option>
                        <option {{ old('expiration') == 168 ? 'selected' : '' }} value="168">7 {{ strtolower(trans_choice('messages.time.days', 7)) }}</option>
                        <option {{ old('expiration') === 0 ? 'selected' : '' }} value="0">@lang('messages.infinite')</option>
                    </select>
                </div>
            </div>
            
            <!-- Appended Input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="custom-expiration">@lang('messages.custom-expiration')</label>
                <div class="col-md-4">
                    <div class="input-group">
                        <input value="{{ old('custom-expiration') }}" id="custom-expiration" name="custom-expiration" class="form-control" placeholder="12" type="text">
                        <span class="input-group-addon">{{ strtolower(trans_choice('messages.time.hours', 2)) }}</span>
                    </div>
                    <p class="help-block">@lang('messages.token-custom-expiration-help')</p>
                </div>
            </div>
            <!-- Textarea -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="note">@lang('messages.note')</label>
                <div class="col-md-4">
                    <textarea class="form-control" id="note" name="note">{{ old('note') }}</textarea>
                </div>
            </div>
            
            <input type="hidden" name="confirming" value="true">
            
            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="generate">{{ trans_choice('messages.generate-tokens', 1) }}</label>
                <div class="col-md-4">
                    <button id="generate" type="submit" name="generate" class="btn btn-block btn-success">@lang('messages.generate')</button>
                </div>
            </div>
        
        </fieldset>
        {!! Form::close() !!}
    @else
        {!! Form::open(['route' => 'token-generate', 'method' => 'POST']) !!}
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
                <td>@lang('messages.note')</td>
                <td><span class="label label-success">{{ $note }}</span></td>
                <input type="hidden" name="note" value="{{ $note }}">
            </tr>
            </tbody>
        </table>
        
        <input type="hidden" name="confirmed" value="true">
        
        <!-- Button -->
        <div class="form-group">
            <div class="col-md-12">
                <button id="generate" type="submit" name="generate" class="btn btn-block btn-success">@lang('messages.generate')</button>
            </div>
        </div>
        {!! Form::close() !!}
    @endif


@endsection