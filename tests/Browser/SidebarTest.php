<?php

namespace Tests\Browser;

use App\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Home;
use Tests\DuskTestCase;

class SidebarTest extends DuskTestCase
{
    // Gerar usuario com ID adequada
    // Gerar usuario com ID aleatoria e testar redirect

    public function testHome()
    {
        $adminUser = factory(User::class)->create([
            'steamid' => '76561198033283983',
        ]);

        $this->browse(function (Browser $browser) use ($adminUser) {
            $browser->loginAs($adminUser)
                    ->visit(new Home())
                    ->click('@home')
                    ->assertRouteIs('home');
        });
    }

    public function testBuyWithSkins()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home());

            $browser->script("document.querySelector('".(new Home())->elements()['@buy-with-skins']."').scrollIntoView()");

            $browser->click('@buy-with-skins')
                    ->assertRouteIs('steam-order.create');
        });
    }

    public function testBuyWithTokens()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home());

            $browser->script("document.querySelector('".(new Home())->elements()['@buy-with-tokens']."').scrollIntoView()");

            $browser->click('@buy-with-tokens')
                    ->assertRouteIs('token-order.create');
        });
    }

    public function testLogs()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home());

            $browser->script("document.querySelector('".(new Home())->elements()['@logs']."').scrollIntoView()");

            $browser->click('@logs')
                    ->assertRouteIs('daemon-logs');
        });
    }

    public function testStdout()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home());

            $browser->script("document.querySelector('".(new Home())->elements()['@stdout']."').scrollIntoView()");

            $browser->click('@stdout')
                    ->assertRouteIs('daemon-stdout');
        });
    }

    public function testStderr()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home());

            $browser->script("document.querySelector('".(new Home())->elements()['@stderr']."').scrollIntoView()");

            $browser->click('@stderr')
                    ->assertRouteIs('daemon-stderr');
        });
    }

    public function testOrders()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home());

            $browser->script("document.querySelector('".(new Home())->elements()['@orders']."').scrollIntoView()");

            $browser->click('@orders')
                    ->assertRouteIs('orders');
        });
    }

    public function testConfirmations()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home());

            $browser->script("document.querySelector('".(new Home())->elements()['@confirmations']."').scrollIntoView()");

            $browser->click('@confirmations')
                    ->assertRouteIs('confirmations');
        });
    }

    public function testTokens()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home());

            $browser->script("document.querySelector('".(new Home())->elements()['@tokens']."').scrollIntoView()");

            $browser->click('@tokens')
                    ->assertRouteIs('tokens.index');
        });
    }

    public function testSettings()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home());

            $browser->script("document.querySelector('".(new Home())->elements()['@settings']."').scrollIntoView()");

            $browser->click('@settings')
                    ->assertRouteIs('settings');
        });
    }

    public function testGenerateTokens()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home());

            $browser->script("document.querySelector('".(new Home())->elements()['@generate-tokens']."').scrollIntoView()");

            $browser->click('@generate-tokens')
                    ->assertRouteIs('tokens.create');
        });
    }

    public function testUsers()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home());

            $browser->script("document.querySelector('".(new Home())->elements()['@users']."').scrollIntoView()");

            $browser->click('@users')
                    ->assertRouteIs('users.index');
        });
    }

    public function testLaravelLogs()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home());

            $browser->script("document.querySelector('".(new Home())->elements()['@laravel-logs']."').scrollIntoView()");

            $browser->click('@laravel-logs')
                    ->assertRouteIs('laravel-logs');
        });
    }

    public function testOpskinsUpdater()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home());

            $browser->script("document.querySelector('".(new Home())->elements()['@opskins-updater']."').scrollIntoView()");

            $browser->click('@opskins-updater')
                    ->assertRouteIs('opskins-update-form');
        });
    }

    public function testAppSettings()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home());

            $browser->script("document.querySelector('".(new Home())->elements()['@app-settings']."').scrollIntoView()");

            $browser->click('@app-settings')
                    ->assertRouteIs('laravel-settings-ui');
        });
    }
}
