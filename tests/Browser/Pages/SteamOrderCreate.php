<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class SteamOrderCreate extends Page
{
	/**
	 * Get the URL for the page.
	 *
	 * @return string
	 */
	public function url()
	{
		return '/steam-orders/create';
	}

	/**
	 * Assert that the browser is on the page.
	 *
	 * @param  Browser $browser
	 *
	 * @return void
	 */
	public function assert(Browser $browser)
	{
		$browser->assertPathIs($this->url())
				->assertSee('Select your items you want to trade')
				->assertSee('P250 | Franklin (Factory New)')
				->assertSee('FAMAS | Styx (Field-Tested)');
	}

	/**
	 * Get the element shortcuts for the page.
	 *
	 * @return array
	 */
	public function elements()
	{
		return [
			'@item1'       => '#checkbox-11',
			'@item1-label' => '#checkbox-label-11',
			'@item2'       => '#checkbox-12',
			'@item2-label' => '#checkbox-label-12',
			'@send'        => '#submit-items',
		];
	}
}
