<?php

namespace Tests\Browser;

use App\Order;
use App\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\SteamOrderCreate;
use Tests\DuskTestCase;

class SteamOrderTest extends DuskTestCase
{
    public function testSteamOrderCanBeCreated()
    {
        $adminUser = factory(User::class)->create([
            'steamid' => '76561198033283983',
        ]);

        $this->browse(function (Browser $browser) use ($adminUser) {
            $browser->loginAs($adminUser)
                    ->visit(new SteamOrderCreate())
                    ->scrollToViewAndClick('@item1-label')
                    ->scrollToViewAndClick('@item2-label')
                    ->scrollToViewAndClick('@send')
                    ->assertSee('Order created successfully!')
                    ->assertSee('TradeOfferNotSent')
                    ->click('#send-trade-link')
                    ->assertSee('Trade offer sent')
                    ->assertSee('Active');

            $orderid = $browser->text('#public-id');

            Artisan::call('steamorders:refresh');

            $browser->visitRoute('steam-orders.show', Order::where('public_id', $orderid)->first())
                    ->assertSee('Accepted')
                    ->assertSee('Create confirmation');
        });
    }
}
