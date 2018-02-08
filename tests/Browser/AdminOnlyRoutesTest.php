<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class AdminOnlyRoutesTest extends DuskTestCase
{
    public function testDaemonLogin()
    {
        $normalUser = factory(User::class)->create([
            'steamid' => '76561198033283935',
        ]);

        $this->browse(function (Browser $browser) use ($normalUser) {
            $browser->loginAs($normalUser)
                    ->visitRoute('daemon-login')
                    ->assertRouteIs('home');
        });
    }

    public function testDaemonLogs()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('daemon-logs')
                    ->assertRouteIs('home');
        });
    }

    public function testDaemonStdout()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('daemon-stdout')
                    ->assertRouteIs('home');
        });
    }

    public function testStderr()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('daemon-stderr')
                    ->assertRouteIs('home');
        });
    }

    public function testGenerateToken()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('tokens.create')
                    ->assertRouteIs('home');
        });
    }

    public function testUsersList()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('users.index')
                    ->assertRouteIs('home');
        });
    }

    public function testLaravelLogs()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('laravel-logs')
                    ->assertRouteIs('home');
        });
    }

    public function testOpskinsUpdater()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('opskins-update-form')
                    ->assertRouteIs('home');
        });
    }

    public function testAppSettings()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('laravel-settings-ui')
                    ->assertRouteIs('home');
        });
    }

}
