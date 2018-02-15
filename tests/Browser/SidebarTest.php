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
                    ->scrollToViewNativeAndClick('@home')
                    ->assertRouteIs('home');
        });
    }

    /**
     * @test
     * @group member
     * @group link
     */
    public function testBuyWithSkins()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@buy-with-skins')
                    ->assertRouteIs('steam-orders.create');
        });
    }

    public function testBuyWithTokens()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@buy-with-tokens')
                    ->assertRouteIs('token-orders.create');
        });
    }

    public function testLogs()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@logs')
                    ->assertRouteIs('daemon-logs');
        });
    }

    public function testStdout()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@stdout')
                    ->assertRouteIs('daemon-stdout');
        });
    }

    public function testStderr()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@stderr')
                    ->assertRouteIs('daemon-stderr');
        });
    }

    public function testOrders()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@orders')
                    ->assertRouteIs('orders.index');
        });
    }

    public function testConfirmations()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@confirmations')
                    ->assertRouteIs('confirmations.index');
        });
    }

    public function testTokens()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@tokens')
                    ->assertRouteIs('tokens.index');
        });
    }

    public function testSettings()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@settings')
                    ->assertRouteIs('users.settings');
        });
    }

    public function testGenerateTokens()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@generate-tokens')
                    ->assertRouteIs('tokens.create');
        });
    }

    public function testAdminsSimple()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@admins-simple')
                    ->assertRouteIs('admins-simple-preview');
        });
    }

    public function testUsers()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@users')
                    ->assertRouteIs('users.index');
        });
    }

    public function testLaravelLogs()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@laravel-logs')
                    ->assertRouteIs('laravel-logs');
        });
    }

    public function testServersList()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@server-list')
                    ->assertRouteIs('servers.index');
        });
    }

    public function testOpskinsUpdater()
    {
        $adminUser = factory(User::class)->create([
            'steamid' => '76561198033283983',
        ]);

        $this->browse(function (Browser $browser) use ($adminUser) {
            $browser->loginAs($adminUser)
                    ->visit(new Home())
                    ->scrollToViewNativeAndClick('@opskins-updater')
                    ->assertRouteIs('opskins-update-form');
        });
    }

    public function testAppSettings()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home())
                    ->scrollToViewNativeAndClick('@app-settings')
                    ->assertRouteIs('laravel-settings-ui');
        });
    }
}
