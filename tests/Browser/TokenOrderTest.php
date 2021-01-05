<?php

namespace Tests\Browser;

use App\Order;
use App\Token;
use App\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TokenOrderTest extends DuskTestCase
{
    public function testTokenCanBeUsedToCreateTokensOrder()
    {
        $adminUser = factory(User::class)->create([
            'steamid' => '76561198033283983',
        ]);

        $token = factory(Token::class)->create([
            'token_order_id' => null,
        ]);

        $this->browse(function (Browser $browser) use ($adminUser, $token) {
            $browser->loginAs($adminUser)
                    ->visit(new \Tests\Browser\Pages\TokenOrderCreate())
                    ->value('@token', $token->token)
                    ->click('@use-token')
                    ->assertSee('Viewing token')
                    ->assertSee($token->token)
                    ->assertSee($token->duration.' days')
                    ->assertSee($token->expiration.' hours')
                    ->assertSee($token->note)
                    ->assertSee('Unused')
                    ->click('@confirm')
                    ->assertSee('Create confirmation');
        });
    }

    public function testTokenOrderCanGenerateConfirmation()
    {
        $adminUser = factory(User::class)->create([
            'steamid' => '76561198033283983',
        ]);

        $token = factory(Token::class)->create([
            'token_order_id' => null,
        ]);

        $this->browse(function (Browser $browser) use ($adminUser, $token) {
            $browser->loginAs($adminUser)
                    ->visit(new \Tests\Browser\Pages\TokenOrderCreate())
                    ->value('@token', $token->token)
                    ->click('@use-token')
                    ->assertSee('Viewing token')
                    ->assertSee($token->token)
                    ->assertSee($token->duration.' days')
                    ->assertSee($token->expiration.' hours')
                    ->assertSee($token->note)
                    ->assertSee('Unused')
                    ->click('@confirm')
                    ->assertSee('Create confirmation')
                    ->click('@confirm')
                    ->assertSee('Confirmation Public ID')
                    ->assertSee('Confirmed');
        });
    }

    public function testOrderWithExtraTokensAllowsGenerationOfExtraTokens()
    {
        $adminUser = factory(User::class)->create([
            'steamid' => '76561198033283983',
        ]);

        $order = factory(Order::class)->create([
            'orderable_type' => 'App\SteamOrder',
            'user_id'        => $adminUser->id,
            'extra_tokens'   => 4,
        ]);

        $token = factory(Token::class)->create([
        ]);

        $this->browse(function (Browser $browser) use ($adminUser) {
            $browser->loginAs($adminUser)
                    ->visit(new \Tests\Browser\Pages\TokensIndex())
                    ->click('@generate-extra-tokens')
                    ->assertSee('Extra token generated')
                    ->assertRouteIs('tokens.index');
        });
    }
}
