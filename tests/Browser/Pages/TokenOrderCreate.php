<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class TokenOrderCreate extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/token-orders/create';
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
                ->assertSee('Token')
                ->assertSee('Use token');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@token'     => '#token',
            '@use-token' => '#use-token',
            '@confirm'   => 'div.container-fluid > div > div.col-sm-9.col-sm-offset-3.col-md-10.col-md-offset-2.main > form > button',
        ];
    }
}
