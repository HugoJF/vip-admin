@extends('layouts.app')

@section('head')
    <link href="{{ asset('css/bs-wizard.css') }}" rel="stylesheet">
@endsection

@section('content')
    <h1>Order #{{ $order->public_id }}</h1>

    <div class="row bs-wizard" style="border-bottom:0;">
        <div class="col-xs-offset-2 col-xs-2 bs-wizard-step {{ $tokenOrder->step() >= 1 ? ($tokenOrder->step() > 1 ? 'complete' : 'active') : 'disabled' }}">
            <div class="text-center bs-wizard-stepnum">@lang('messages.step', ['step' => 1])</div>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>

            <div class="bs-wizard-info text-center">@lang('messages.token-order-step-1')</div>
        </div>

        <div class="col-xs-2 bs-wizard-step {{ $tokenOrder->step() >= 2 ? ($tokenOrder->step() > 2 ? 'complete' : 'active') : 'disabled' }}"><!-- complete -->
            <div class="text-center bs-wizard-stepnum">@lang('messages.step', ['step' => 2])</div>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>

            <div class="bs-wizard-info text-center">@lang('messages.token-order-step-2')</div>
        </div>

        <div class="col-xs-2 bs-wizard-step {{ $tokenOrder->step() >= 3 ? ($tokenOrder->step() > 3 ? 'complete' : 'active') : 'disabled' }}"><!-- complete -->
            <div class="text-center bs-wizard-stepnum">@lang('messages.step', ['step' => 3])</div>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>

            <div class="bs-wizard-info text-center">@lang('messages.token-order-step-3')</div>
        </div>

        <div class="col-xs-2 bs-wizard-step {{ $tokenOrder->step() >= 4 ? ($tokenOrder->step() > 4 ? 'complete' : 'active') : 'disabled' }}"><!-- active -->
            <div class="text-center bs-wizard-stepnum">@lang('messages.step', ['step' => 4])</div>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>

            <div class="bs-wizard-info text-center">@lang('messages.token-order-step-4')</div>
        </div>
    </div>


    <div class="col-sm-12 col-md-12">
        <h3>@lang('messages.details')</h3>
        <table class="table table-hover">
            <tbody>
            <tr>
                <td>@lang('messages.duration')</td>
                <td><span class="label label-success">{{ $order->duration }} {{ strtolower(trans_choice('messages.time.days', 2)) }}</span></td>
            </tr>
            <tr>
                <td>@lang('messages.order-public-id')</td>
                <td><span class="label label-success">{{ $order->public_id }}</span></td>
            </tr>
            <tr>
                <td>@lang('messages.current-state')</td>
                <td><span class="label label-{{ $tokenOrder->status()['class'] }}">{{ $tokenOrder->status()['text'] }}</span></td>
            </tr>
            <tr>
                <td>@lang('messages.last-update')</td>
                <td><span class="label label-success">{{ $tokenOrder->updated_at->diffForHumans() }}</span></td>
            </tr>
            @if($order->confirmation)
                <tr>
                    <td>@lang('messages.confirmation-starting-period')</td>
                    <td><span class="label label-success">{{ $order->confirmation->start_period }}</span></td>
                </tr>
                <tr>
                    <td>@lang('messages.confirmation-ending-period')</td>
                    <td><span class="label label-success">{{ $order->confirmation->end_period }}</span></td>
                </tr>
                <tr>
                    <td>@lang('messages.confirmation-public-id ')</td>
                    <td><span class="label label-success">{{ $order->confirmation->public_id }}</span></td>
                </tr>
            @endif
            </tbody>
        </table>

        @if(!$order->confirmation)
                <a class="btn btn-success btn-lg btn-block" href="{{ route('confirmations.store', $order) }}">@lang('messages.confirmation-create')</a>
        @endif
    </div>


@endsection