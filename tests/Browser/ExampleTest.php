<?php

namespace Tests\Browser;

use App\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Home;
use Tests\Browser\Pages\Settings;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    public function testNormalUserCantViewAppSettings()
    {
        $normalUser = factory(User::class)->create([
                'steamid' => '76561198033283935',
        ]);

        $this->browse(function (Browser $browser) use ($normalUser) {
            $browser->loginAs($normalUser)
                    ->visitRoute('laravel-settings-ui')
                    ->assertRouteIs('homes');
        });
    }
}
