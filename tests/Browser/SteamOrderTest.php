<?php

namespace Tests\Browser;

use App\Classes\Daemon;
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
        Daemon::startMock();
        Daemon::fileMock('inventory', 'inventory-1518287051.txt');
        Daemon::fileMock('status', 'status-1518238964.txt');
        Daemon::fileMock('sendTradeOffer', 'sendTradeOffer-1518287172.txt');
        Daemon::fileMock('getTradeOffer', 'getTradeOffer-1518287519.txt');

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
                    ->assertSee('Trade Offer not sent')
                    ->click('#send-trade-link')
                    ->assertSee('Trade offer sent')
                    ->assertSee('Active');

            $orderid = $browser->text('#public-id');

            Artisan::call('steamorders:refresh');

            $browser->visitRoute('steam-order.show', Order::where('public_id', $orderid)->first())
                    ->assertSee('Accepted')
                    ->assertSee('Create confirmation');
        });
    }
}
