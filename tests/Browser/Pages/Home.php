<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class Home extends BasePage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/';
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
                ->assertSee('Bem vindo');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@home'            => '#collapseZero > ul > li:nth-child(1) > a',

            '@buy-with-skins'  => '#collapseOne > ul > li:nth-child(1) > a',
            '@buy-with-tokens' => '#collapseOne > ul > li:nth-child(2) > a',

            '@logs'            => '#collapseTwo > ul > li:nth-child(1) > a',
            '@stdout'          => '#collapseTwo > ul > li:nth-child(2) > a',
            '@stderr'          => '#collapseTwo > ul > li:nth-child(3) > a',

            '@orders'          => '#collapseThree > ul > li:nth-child(1) > a',
            '@confirmations'   => '#collapseThree > ul > li:nth-child(2) > a',
            '@tokens'          => '#collapseThree > ul > li:nth-child(3) > a',
            '@settings'        => '#collapseThree > ul > li:nth-child(4) > a',

            '@generate-tokens'  => '#collapseFour > ul > li:nth-child(1) > a',
            '@users'            => '#collapseFour > ul > li:nth-child(2) > a',
            '@laravel-logs'     => '#collapseFour > ul > li:nth-child(3) > a',
            '@opskins-updater'  => '#collapseFour > ul > li:nth-child(5) > a',
            '@app-settings'     => '#collapseFour > ul > li:nth-child(6) > a',
        ];
    }
}
