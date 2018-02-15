<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('login', 'AuthController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('admins_simple', 'ConfirmationsController@generateAdminsSimple')->middleware(['daemon', 'admin'])->name('admins-simple');
Route::get('admins_simple/preview', 'ConfirmationsController@viewAdminsSimple')->middleware(['daemon', 'admin'])->name('admins-simple-preview');
Route::get('confirmations/generate/{order}', 'ConfirmationsController@generate')->middleware(['auth', 'accepted'])->name('create-confirmation')->middleware('can:create,App\Confirmation');
Route::delete('confirmations/{confirmation}', 'ConfirmationsController@delete')->middleware(['auth', 'admin'])->name('confirmations.delete')->middleware('can:delete,confirmation');
Route::patch('confirmations/{confirmation}/restore', 'ConfirmationsController@restore')->middleware(['auth', 'admin'])->name('confirmations.restore')->middleware('can:delete,confirmation');
Route::get('confirmations/{confirmation}/edit', 'ConfirmationsController@edit')->middleware(['auth', 'admin'])->name('confirmations.edit');
Route::patch('confirmations/{confirmation}', 'ConfirmationsController@update')->middleware(['auth', 'admin'])->name('confirmations.update');

Route::get('orders', 'OrdersController@index')->middleware('auth', 'accepted')->name('orders.index');
Route::get('orders/{order}', 'OrdersController@show')->middleware('auth', 'accepted')->name('orders.show');
Route::get('orders/{order}/edit', 'OrdersController@edit')->middleware('auth', 'accepted', 'admin')->name('orders.edit');
Route::delete('orders/{order}', 'OrdersController@delete')->middleware('auth', 'accepted', 'admin')->name('orders.delete');
Route::patch('orders/{order}', 'OrdersController@update')->middleware('auth', 'accepted', 'admin')->name('orders.update');
Route::get('confirmations', 'ConfirmationsController@view')->middleware('auth', 'accepted')->name('confirmations.index');
Route::get('sync-server', 'ConfirmationsController@syncServer')->middleware('auth', 'admin')->name('sync-server');

Route::group(['middleware' => ['admin', 'daemon.online']], function () {
	Route::get('laravel/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('laravel-logs');
	Route::get('daemon/logs', 'DaemonController@logs')->name('daemon-logs');
	Route::get('daemon/stdout', 'DaemonController@stdout')->name('daemon-stdout');
	Route::get('daemon/stderr', 'DaemonController@stderr')->name('daemon-stderr');
	Route::get('daemon/kill', 'DaemonController@kill')->name('daemon-kill');

	Route::get('daemon/login', 'DaemonController@login')->name('daemon-login');
	Route::post('daemon/login', 'DaemonController@loginPost')->name('daemon-login-post');

	Route::get('/settings', ['as' => 'laravel-settings-ui', 'uses' => '\\Imtigger\\LaravelSettingsUI\\Controller@get']);
	Route::post('/settings', ['as' => 'laravel-settings-ui.post', 'uses' => '\\Imtigger\\LaravelSettingsUI\\Controller@post']);

	Route::get('/users', 'UserController@index')->name('users.index');
});

Route::group(['middleware' => ['auth']], function () {
	Route::get('/', 'UserController@home')->name('home');
	Route::get('accept', 'UserController@accept')->name('accept');
	Route::get('users/settings', 'UserController@settings')->name('settings');
	Route::post('users/settings', 'UserController@settingsUpdate')->name('settings.update');
	Route::patch('users/{user}/ban', 'UserController@ban')->name('users.ban')->middleware('can:ban,user');
	Route::patch('users/{user}/unban', 'UserController@unban')->name('users.unban')->middleware('can:unban,user');

	Route::group(['middleware' => ['admin']], function () {
		Route::get('opskins-updater', 'OPSkinsController@updateForm')->name('opskins-update-form');
		Route::post('opskins-updater', 'OPSkinsController@updateFromData')->name('opskins-update-form-post');
	});

	Route::group(['middleware' => ['accepted']], function () {
		Route::get('token-orders/{order}', 'TokenOrderController@view')->name('token-order.show')->middleware('can:view,order');

		Route::post('tokens-orders/store', 'TokenOrderController@store')->name('token-order.store')->middleware('can:create,App\Order');
		Route::get('tokens-orders/create', 'TokenOrderController@create')->name('token-order.create')->middleware('can:create,App\Order');

		Route::group(['middleware' => ['admin']], function () {
			Route::get('tokens/create', 'TokenController@create')->name('tokens.create')->middleware('can:create,App\Token');
			Route::post('tokens/create', 'TokenController@create')->name('tokens.create')->middleware('can:create,App\Token');

			Route::get('servers/{server}/edit', 'ServersController@edit')->name('servers.edit')->middleware('can:edit,server');
			Route::get('servers/create', 'ServersController@create')->name('servers.create')->middleware('can:create,App\Server');
			Route::get('servers', 'ServersController@index')->name('servers.index');
			Route::post('servers', 'ServersController@store')->name('servers.store')->middleware('can:create,App\Server');
			Route::patch('servers/{server}', 'ServersController@update')->name('servers.update')->middleware('can:update,server');
			Route::delete('servers/{server}', 'ServersController@delete')->name('servers.delete')->middleware('can:delete,server');
		});

		Route::post('tokens/extra', 'TokenController@storeExtra')->name('tokens.storeExtra')->middleware('can:create,App\Token');
		Route::get('tokens/{token}', 'TokenController@show')->name('tokens.show')->middleware('can:view,token');
		Route::get('tokens/{token}/edit', 'TokenController@edit')->name('tokens.edit')->middleware('can:edit,token');
		Route::patch('tokens/{token}/restore', 'TokenController@restore')->name('tokens.restore')->middleware('can:delete,token');
		Route::delete('tokens/{token}', 'TokenController@delete')->name('tokens.delete')->middleware('can:delete,token');
		Route::patch('tokens/{token}', 'TokenController@update')->name('tokens.update')->middleware('can:edit,token');
		Route::post('tokens', 'TokenController@store')->name('tokens.store')->middleware('can:create,App\Token');
		Route::get('tokens', 'TokenController@index')->name('tokens.index');

		Route::group(['middleware' => ['tradelink', 'daemon']], function () {
			Route::post('steam-orders', 'SteamOrderController@store')->name('steam-order.store');
			Route::get('steam-orders/create', 'SteamOrderController@create')->name('steam-order.create')->middleware('can:create,App\Order');
			Route::get('steam-orders/{order}', 'SteamOrderController@show')->name('steam-order.show')->middleware('can:view,order');
			Route::get('steam-orders/{order}/send-tradeoffer', 'SteamOrderController@sendTradeOffer')->name('steam-order.send-tradeoffer')->middleware('can:view,order');
		});
	});
});
