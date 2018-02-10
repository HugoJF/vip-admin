<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class Settings extends Page
{
	/**
	 * Get the URL for the page.
	 *
	 * @return string
	 */
	public function url()
	{
		return '/users/settings';
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
				->assertSee('Name')
				->assertSee('Trade Link');
	}

	public function changeSettings(Browser $browser, $name, $tradelink)
	{
		$browser->type('@name', $name)
				->type('@tradelink', $tradelink)
				->click('@submit');
	}

	/**
	 * Get the element shortcuts for the page.
	 *
	 * @return array
	 */
	public function elements()
	{
		return [
			'@name'      => '#name',
			'@tradelink' => '#tradelink',
			'@submit'    => '#submit',
		];
	}
}
