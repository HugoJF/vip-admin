@extends('layouts.app')

@section('head')
    <link href="{{ asset('/css/bs-wizard.css') }}" rel="stylesheet">
@endsection

@section('content')
    <h1>Order #{{ $order->public_id }}</h1>
    
    <div class="row bs-wizard" style="border-bottom:0;">
        
        <div class="col-xs-3 bs-wizard-step {{ $mpOrder->step() >= 1 ? ($mpOrder->step() > 1 ? 'complete' : 'active') : 'disabled' }}">
            <div class="text-center bs-wizard-stepnum">@lang('messages.step', ['step' => 1])</div>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>
            
            <div class="bs-wizard-info text-center">@lang('messages.mp-order-step-1')</div>
        </div>
        
        <div class="col-xs-3 bs-wizard-step {{ $mpOrder->step() >= 2 ? ($mpOrder->step() > 2 ? 'complete' : 'active') : 'disabled' }}"><!-- complete -->
            <div class="text-center bs-wizard-stepnum">@lang('messages.step', ['step' => 2])</div>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>
            
            <div class="bs-wizard-info text-center">@lang('messages.mp-order-step-2')</div>
        </div>
        
        <div class="col-xs-3 bs-wizard-step {{ $mpOrder->step() >= 3 ? ($mpOrder->step() > 3 ? 'complete' : 'active') : 'disabled' }}"><!-- complete -->
            <div class="text-center bs-wizard-stepnum">@lang('messages.step', ['step' => 3])</div>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>
            
            <div class="bs-wizard-info text-center">@lang('messages.mp-order-step-3')</div>
        </div>
        
        <div class="col-xs-3 bs-wizard-step {{ $mpOrder->step() >= 4 ? ($mpOrder->step() > 4 ? 'complete' : 'active') : 'disabled' }}"><!-- complete -->
            <div class="text-center bs-wizard-stepnum">@lang('messages.step', ['step' => 4])</div>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>
            
            <div class="bs-wizard-info text-center">@lang('messages.mp-order-step-4')</div>
        </div>
    
    </div>
    
    
    <div class="col-sm-12 col-md-12">
        <h3>Details</h3>
        <table class="table table-hover">
            <tbody>
            <tr>
                <td>@lang('messages.duration')</td>
                <td>
                    <span class="label label-success">{{ $order->duration }} {{ strtolower(trans_choice('messages.time.days', $order->duration)) }}</span>
                </td>
            </tr>
            <tr>
                <td>Total Value</td>
                <td>
                    <span class="label label-success">R$ {{ round($mpOrder->amount / 100, 3) }}</span>
                </td>
            </tr>
            <tr>
                <td>@lang('messages.order-public-id')</td>
                <td>
                    <span id="public-id" class="label label-success">{{ $order->public_id }}</span>
                </td>
            </tr>
            <tr>
                <td>@lang('messages.current-state')</td>
                <td>
                    <span title="{{ $mpOrder->mp_order_status }}" class="label label-{{ $mpOrder->status()['class'] }}">{{ $mpOrder->status()['text'] }}</span>
                </td>
            </tr>
            <tr>
                <td>@lang('messages.mp_preference_id')</td>
                <td>
                    <a href="{{ route('mp-orders.debug.preference', $mpOrder->mp_preference_id) }}">
                        <span class="label label-success">{{ $mpOrder->mp_preference_id ?? "N/A" }}</span>
                    </a>
                </td>
            </tr>
            <tr>
                <td>@lang('messages.mp_order_id')</td>
                <td>
                    <a href="{{ route('mp-orders.debug.order', $mpOrder->mp_order_id) }}">
                        <span class="label label-success">{{ $mpOrder->mp_order_id ?? "N/A" }}</span>
                    </a>
                </td>
            </tr>
            <tr>
                <td>@lang('messages.mp_payment_id')</td>
                <td>
                    <a href="{{ route('mp-orders.debug.payment', $mpOrder->mp_payment_id) }}">
                        <span class="label label-success">{{ $mpOrder->mp_payment_id ?? "N/A" }}</span>
                    </a>
                </td>
            </tr>
            <tr>
                <td>@lang('messages.last-update')</td>
                <td>
                    <span class="label label-success">{{ $mpOrder->updated_at->diffForHumans() }}</span>
                </td>
            </tr>
            @if($order->confirmation)
                <tr>
                    <td>@lang('messages.confirmation-starting-period')</td>
                    <td>
                        <span class="label label-success">{{ $order->confirmation->start_period }}</span>
                    </td>
                </tr>
                <tr>
                    <td>@lang('messages.confirmation-ending-period')</td>
                    <td>
                        <span class="label label-success">{{ $order->confirmation->end_period }}</span>
                    </td>
                </tr>
                <tr>
                    <td>@lang('messages.confirmation-public-id ')</td>
                    <td>
                        <span class="label label-success">{{ $order->confirmation->public_id }}</span>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
        
        @if(!$order->confirmation)
            <h3>@lang('messages.actions')</h3>
            <a class="btn btn-success btn-lg btn-block" href="{{ route('confirmations.store', $order) }}">@lang('messages.confirmation-create')</a>
        @endif
        @if(Auth::user()->isAdmin())
            <a class="btn btn-primary btn-block" href="{{ route('mp-orders.recheck', $order) }}"> @lang('messages.recheck')</a>
        @endif
    </div>

@endsection