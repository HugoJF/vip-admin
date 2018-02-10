<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class TokensCreate extends Page
{
	/**
	 * Get the URL for the page.
	 *
	 * @return string
	 */
	public function url()
	{
		return '/tokens/create';
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
				->assertSee('Token generation form')
				->assertSee('Duration')
				->assertSee('Expiration')
				->assertSee('Generate');
	}

	/**
	 * Get the element shortcuts for the page.
	 *
	 * @return array
	 */
	public function elements()
	{
		return [
			'@generate'          => '#generate',
			'@duration'          => '#duration',
			'@expiration'        => '#expiration',
			'@custom-duration'   => '#custom-duration',
			'@custom-expiration' => '#custom-expiration',
			'@note'              => '#note',
		];
	}
}
