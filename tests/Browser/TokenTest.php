<?php

namespace Tests\Browser;

use App\Token;
use App\User;
use Tests\Browser\Pages\TokensIndex;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\Home;
use Tests\Browser\Pages\TokensCreate;

class TokenTest extends DuskTestCase
{
	public function testAdminCanCreatePresetToken()
	{
		$adminUser = factory(User::class)->create([
			'steamid' => '76561198033283983',
		]);

		$this->browse(function (Browser $browser) use ($adminUser) {
			$browser->loginAs($adminUser)
					->visit(new TokensCreate)
					->select('@duration', 7)
					->select('@expiration', 4)
					->value('@note', 'This is my test note!')
					->click('@generate')
					->assertRouteIs('tokens.create')
					->assertSee('Token generation confirmation details')
					->assertSee('7 days')
					->assertSee('4 hours')
					->assertSee('This is my test note!')
					->click('@generate')
					->assertSee('Viewing token')
					->assertSee('7 days')
					->assertSee('4 hours')
					->assertSee('This is my test note!');
		});
	}

	public function testAdminCanCreateCustomToken()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new TokensCreate)
					->value('@custom-duration', 33)
					->value('@custom-expiration', 33)
					->value('@note', 'This is my test custom note!')
					->click('@generate')
					->assertRouteIs('tokens.create')
					->assertSee('Token generation confirmation details')
					->assertSee('33 days')
					->assertSee('33 hours')
					->assertSee('This is my test custom note!')
					->click('@generate')
					->assertSee('Viewing token')
					->assertSee('33 days')
					->assertSee('33 hours')
					->assertSee('This is my test custom note!');
		});
	}

	public function testTokenCanBeSeeInIndex()
	{

		$token = factory(Token::class)->create();

		$this->browse(function (Browser $browser) use ($token) {
			$browser->visit(new TokensIndex)
					->assertSee('Current generated Tokens')
					->assertSee($token->token);
		});
	}
}
