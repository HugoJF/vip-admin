@extends('layouts.app')

@section('head')
    <style>
        .bs-wizard {
            margin-top: 40px;
        }

        /*Form Wizard*/
        .bs-wizard {
            border-bottom: solid 1px #e0e0e0;
            padding: 0 0 10px 0;
        }

        .bs-wizard > .bs-wizard-step {
            padding: 0;
            position: relative;
        }

        .bs-wizard > .bs-wizard-step + .bs-wizard-step {
        }

        .bs-wizard > .bs-wizard-step .bs-wizard-stepnum {
            color: #595959;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .bs-wizard > .bs-wizard-step .bs-wizard-info {
            color: #999;
            font-size: 14px;
        }

        .bs-wizard > .bs-wizard-step > .bs-wizard-dot {
            position: absolute;
            width: 30px;
            height: 30px;
            display: block;
            background: #fbe8aa;
            top: 45px;
            left: 50%;
            margin-top: -15px;
            margin-left: -15px;
            border-radius: 50%;
        }

        .bs-wizard > .bs-wizard-step > .bs-wizard-dot:after {
            content: ' ';
            width: 14px;
            height: 14px;
            background: #fbbd19;
            border-radius: 50px;
            position: absolute;
            top: 8px;
            left: 8px;
        }

        .bs-wizard > .bs-wizard-step > .progress {
            position: relative;
            border-radius: 0px;
            height: 8px;
            box-shadow: none;
            margin: 20px 0;
        }

        .bs-wizard > .bs-wizard-step > .progress > .progress-bar {
            width: 0px;
            box-shadow: none;
            background: #fbe8aa;
        }

        .bs-wizard > .bs-wizard-step.complete > .progress > .progress-bar {
            width: 100%;
        }

        .bs-wizard > .bs-wizard-step.active > .progress > .progress-bar {
            width: 50%;
        }

        .bs-wizard > .bs-wizard-step:first-child.active > .progress > .progress-bar {
            width: 0%;
        }

        .bs-wizard > .bs-wizard-step:last-child.active > .progress > .progress-bar {
            width: 100%;
        }

        .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot {
            background-color: #f5f5f5;
        }

        .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot:after {
            opacity: 0;
        }

        .bs-wizard > .bs-wizard-step:first-child > .progress {
            left: 50%;
            width: 50%;
        }

        .bs-wizard > .bs-wizard-step:last-child > .progress {
            width: 50%;
        }

        .bs-wizard > .bs-wizard-step.disabled a.bs-wizard-dot {
            pointer-events: none;
        }
    </style>
@endsection

@section('content')
    <h1>Order #{{ $order->public_id }}</h1>

    <div class="row bs-wizard" style="border-bottom:0;">
        <div class="col-xs-offset-1 col-xs-2 bs-wizard-step {{ $steamOrder->currentStep() >= 1 ? ($steamOrder->currentStep() > 1 ? 'complete' : 'active') : 'disabled' }}">
            <div class="text-center bs-wizard-stepnum">Step 1</div>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>

            <div class="bs-wizard-info text-center">Select your items to trade</div>
        </div>

        <div class="col-xs-2 bs-wizard-step {{ $steamOrder->currentStep() >= 2 ? ($steamOrder->currentStep() > 2 ? 'complete' : 'active') : 'disabled' }}"><!-- complete -->
            <div class="text-center bs-wizard-stepnum">Step 2</div>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>

            <div class="bs-wizard-info text-center">Send the trade offer</div>
        </div>

        <div class="col-xs-2 bs-wizard-step {{ $steamOrder->currentStep() >= 3 ? ($steamOrder->currentStep() > 3 ? 'complete' : 'active') : 'disabled' }}"><!-- complete -->
            <div class="text-center bs-wizard-stepnum">Step 3</div>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>

            <div class="bs-wizard-info text-center">Accept the trade offer</div>
        </div>

        <div class="col-xs-2 bs-wizard-step {{ $steamOrder->currentStep() >= 4 ? ($steamOrder->currentStep() > 4 ? 'complete' : 'active') : 'disabled' }}"><!-- active -->
            <div class="text-center bs-wizard-stepnum">Step 4</div>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>

            <div class="bs-wizard-info text-center">Generate the confirmation</div>
        </div>
        <div class="col-xs-2 bs-wizard-step {{ $steamOrder->currentStep() >= 5 ? ($steamOrder->currentStep() > 5 ? 'complete' : 'active') : 'disabled' }}"><!-- active -->
            <div class="text-center bs-wizard-stepnum">Step 4</div>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>

            <div class="bs-wizard-info text-center">Server files uploaded</div>
        </div>
    </div>


    <div class="col-sm-12 col-md-6">
        <h3>Details</h3>
        <table class="table table-hover">
            <tbody>
            <tr>
                <td>Duration</td>
                <td><span class="label label-success">{{ $duration }} days</span></td>
            </tr>
            <tr>
                <td>Total Value</td>
                <td><span class="label label-success">${{ round($totalValue / 100, 3) }}</span></td>
            </tr>
            <tr>
                <td>Public ID</td>
                <td><span id="public-id" class="label label-success">{{ $order->public_id }}</span></td>
            </tr>
            <tr>
                <td>Current state</td>
                <td><span class="label label-{{ $steamOrder->stateClass() }}">{{ $steamOrder->stateText() }}</span></td>
            </tr>
            <tr>
                <td>Last update</td>
                <td><span class="label label-success">{{ $steamOrder->updated_at->diffForHumans() }}</span></td>
            </tr>
            @if($order->confirmation)
                <tr>
                    <td>Start period</td>
                    <td><span class="label label-success">{{ $order->confirmation->start_period }}</span></td>
                </tr>
                <tr>
                    <td>End period</td>
                    <td><span class="label label-success">{{ $order->confirmation->end_period }}</span></td>
                </tr>
                <tr>
                    <td>Confirmation ID</td>
                    <td><span class="label label-success">{{ $order->confirmation->public_id }}</span></td>
                </tr>
            @endif
            </tbody>
        </table>

        @if(!$order->confirmation)
            <h3>Actions</h3>
            @if($steamOrder->notSent())
                <a id="send-trade-link" class="btn btn-success btn-lg btn-block" href="{{ route('steam-order.send-tradeoffer', $order->public_id) }}">Send Trade Link</a>
            @elseif($steamOrder->active())
                <a class="btn btn-success btn-lg btn-block" target="_blank" href="https://steamcommunity.com/tradeoffer/{{ $steamOrder->tradeoffer_id }}">Open Trade Offer</a>
            @elseif($steamOrder->accepted())
                <a class="btn btn-success btn-lg btn-block" href="{{ route('create-confirmation', $order->public_id) }}">Create confirmation</a>
            @endif
        @endif
    </div>


    <div class="col-sm-12 col-md-6">

        <h3>Items</h3>

        @foreach($items as $item)
            <div class="col-sm-12 col-md-6">
                <p><img height="75px" src="http://steamcommunity-a.akamaihd.net/economy/image/{{ $item->icon_url }}">{{$item->market_name}}</p>
            </div>
        @endforeach
    </div>

@endsection