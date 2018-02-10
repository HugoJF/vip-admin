<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Page as BasePage;
use Laravel\Dusk\Browser;

abstract class Page extends BasePage
{
	/**
	 * Get the global element shortcuts for the site.
	 *
	 * @return array
	 */
	public static function siteElements()
	{
		return [
			'@element' => '#selector',
		];
	}

	public function scrollToView(Browser $browser, $element)
	{
		$browser->script("document.querySelector('" . $this->elements()[ $element ] . "').scrollIntoView()");
	}

	public function scrollToViewAndClick(Browser $browser, $element)
	{
		$browser->script("document.querySelector('" . $this->elements()[ $element ] . "').scrollIntoView()");
		$browser->click($this->elements()[ $element ]);
	}
}
