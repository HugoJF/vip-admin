<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class Home extends Page
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
            '@home'			   => '#home',

            '@buy-with-skins'  => '#buy-with-skins',
            '@buy-with-tokens' => '#buy-with-tokens',
            '@buy-with-mp'     => '#buy-with-mp',

            '@logs'            => '#daemon-logs',
            '@stdout'          => '#daemon-stdout',
            '@stderr'          => '#daemon-stderr',

            '@orders'          => '#orders',
            '@confirmations'   => '#confirmations',
            '@tokens'          => '#tokens',
            '@settings'        => '#settings',

            '@generate-tokens' => '#generate-tokens',
            '@admins-simple'   => '#admins-simple-preview',
            '@users'           => '#users',
            '@laravel-logs'    => '#laravel-logs',
            '@server-list'     => '#server-list',
            '@opskins-updater' => '#opskins-updater',
            '@app-settings'    => '#app-settings',
        ];
    }
}
