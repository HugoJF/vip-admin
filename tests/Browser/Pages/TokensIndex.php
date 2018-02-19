<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class TokensIndex extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/tokens';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url())
                ->assertSee('Current generated Tokens')
                ->assertSee('Token')
				->assertSee('Duration')
				->assertSee('Generate extra tokens')
                ->assertSee('Expiration');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@generate-extra-tokens' => '#generate',
        ];
    }
}
