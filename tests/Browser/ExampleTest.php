<?php

namespace Tests\Browser;

use Tests\Browser\Pages\Settings;
use Tests\Browser\Pages\Home;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
					->assertRouteIs('home');
		});
	}

}
