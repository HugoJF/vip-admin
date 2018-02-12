<?php

namespace App\Providers;

use App\Classes\Daemon;
use App\Events\OrderCreated;
use App\Events\TokenCreated;
use App\Events\UserCreated;
use App\Order;
use App\Token;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // Bootstrap events that trigger emails
        Order::creating(function ($order) {
            event(new OrderCreated($order));
        });

        Token::creating(function ($token) {
            event(new TokenCreated($token));
        });

        User::creating(function ($user) {
            event(new UserCreated($user));
        });

        // Mock when not in production
        if ($this->app->environment('local', 'testing')) {
            Daemon::startMock();
            Daemon::fileMock('inventory', 'inventory-1518287051.txt');
            Daemon::fileMock('status', 'status-1518238964.txt');
            Daemon::fileMock('sendTradeOffer', 'sendTradeOffer-1518287172.txt');
            Daemon::fileMock('getTradeOffer', 'getTradeOffer-1518287519.txt');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Debugbar registering
        if ($this->app->environment() && $this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }

        // Laravel Dusk registering
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
