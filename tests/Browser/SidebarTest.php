<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\Home;

class SidebarTest extends DuskTestCase
{
	// Gerar usuario com ID adequada
	// Gerar usuario com ID aleatoria e testar redirect

	public function testHome()
	{
		$adminUser = factory(User::class)->create([
			'steamid' => '76561198033283983',
		]);

		$this->browse(function (Browser $browser) use ($adminUser) {
			$browser->loginAs($adminUser)
					->visit(new Home)
					->scrollToViewAndClick('@home')
					->assertRouteIs('home');
		});
	}

	/**
	 * @test
	 * @group member
	 * @group link
	 */
	public function testBuyWithSkins()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new Home)
					->scrollToViewAndClick('@buy-with-skins')
					->assertRouteIs('steam-order.create');
		});
	}

	public function testBuyWithTokens()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new Home)
					->scrollToViewAndClick('@buy-with-tokens')
					->assertRouteIs('token-order.create');
		});
	}

	public function testLogs()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new Home)
					->scrollToViewAndClick('@logs')
					->assertRouteIs('daemon-logs');
		});
	}

	public function testStdout()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new Home)
					->scrollToViewAndClick('@stdout')
					->assertRouteIs('daemon-stdout');
		});
	}

	public function testStderr()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new Home)
					->scrollToViewAndClick('@stderr')
					->assertRouteIs('daemon-stderr');
		});
	}

	public function testOrders()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new Home)
					->scrollToViewAndClick('@orders')
					->assertRouteIs('orders');
		});
	}

	public function testConfirmations()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new Home)
					->scrollToViewAndClick('@confirmations')
					->assertRouteIs('confirmations');
		});
	}

	public function testTokens()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new Home)
					->scrollToViewAndClick('@tokens')
					->assertRouteIs('tokens.index');
		});
	}

	public function testSettings()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new Home)
					->scrollToViewAndClick('@settings')
					->assertRouteIs('settings');
		});
	}

	public function testGenerateTokens()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new Home)
					->scrollToViewAndClick('@generate-tokens')
					->assertRouteIs('tokens.create');
		});
	}

	public function testUsers()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new Home)
					->scrollToViewAndClick('@users')
					->assertRouteIs('users.index');
		});
	}

	public function testLaravelLogs()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new Home)
					->scrollToViewAndClick('@laravel-logs')
					->assertRouteIs('laravel-logs');
		});
	}

	public function testOpskinsUpdater()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new Home)
					->scrollToViewAndClick('@opskins-updater')
					->assertRouteIs('opskins-update-form');
		});
	}

	public function testAppSettings()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(new Home)
					->scrollToViewAndClick('@app-settings')
					->assertRouteIs('laravel-settings-ui');
		});
	}
}
