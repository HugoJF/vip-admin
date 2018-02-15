<?php

/**
 * Authentication
 */
Route::get('login', 'AuthController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');


/**
 * Server Files
 */
Route::group(['middleware' => ['daemon', 'admin']], function () {
	Route::get('admins_simple', 'ConfirmationsController@generateAdminsSimple')->name('admins-simple');
	Route::get('admins_simple/preview', 'ConfirmationsController@viewAdminsSimple')->name('admins-simple-preview');
});


/**
 * Daemon controller
 */
Route::group(['middleware' => ['admin', 'daemon.online']], function () {
	Route::get('laravel/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('laravel-logs');
	Route::get('daemon/logs', 'DaemonController@logs')->name('daemon-logs');
	Route::get('daemon/stdout', 'DaemonController@stdout')->name('daemon-stdout');
	Route::get('daemon/stderr', 'DaemonController@stderr')->name('daemon-stderr');
	Route::get('daemon/kill', 'DaemonController@kill')->name('daemon-kill');

	Route::get('daemon/login', 'DaemonController@login')->name('daemon-login');
	Route::post('daemon/login', 'DaemonController@loginPost')->name('daemon-login-post');
});


/**
 * Application Settings
 */
Route::group(['middleware' => ['admin', 'daemon.online']], function () {
	Route::get('/settings', ['as' => 'laravel-settings-ui', 'uses' => '\\Imtigger\\LaravelSettingsUI\\Controller@get']);
	Route::post('/settings', ['as' => 'laravel-settings-ui.post', 'uses' => '\\Imtigger\\LaravelSettingsUI\\Controller@post']);
});


/**
 * Confirmation controller
 */
Route::group(['middleware' => ['auth', 'accepted']], function () {
	Route::get('confirmations/generate/{order}', 'ConfirmationsController@generate')->name('confirmations.store')->middleware('can:create,App\Confirmation');
	Route::get('confirmations', 'ConfirmationsController@view')->name('confirmations.index');

	Route::group(['middleware' => ['admin']], function () {
		Route::delete('confirmations/{confirmation}', 'ConfirmationsController@delete')->name('confirmations.delete')->middleware('can:delete,confirmation');
		Route::patch('confirmations/{confirmation}/restore', 'ConfirmationsController@restore')->name('confirmations.restore')->middleware('can:delete,confirmation');
		Route::get('confirmations/{confirmation}/edit', 'ConfirmationsController@edit')->name('confirmations.edit')->middleware('can:edit,confirmation');
		Route::patch('confirmations/{confirmation}', 'ConfirmationsController@update')->name('confirmations.update')->middleware('can:update,confirmation');
		Route::get('sync-server', 'ConfirmationsController@syncServer')->name('servers.sync');
	});
});


/**
 * Orders controller
 */
Route::group(['middleware' => ['auth', 'accepted']], function () {
	Route::group(['middleware' => ['admin']], function () {
		Route::get('orders/{order}/edit', 'OrdersController@edit')->name('orders.edit');
		Route::delete('orders/{order}', 'OrdersController@delete')->name('orders.delete');
		Route::patch('orders/{order}', 'OrdersController@update')->name('orders.update');
	});

	Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');
	Route::get('orders', 'OrdersController@index')->name('orders.index');
});


/**
 * OPSkins controller
 */
Route::group(['middlerware' => ['auth', 'admin']], function () {
	Route::get('opskins-updater', 'OPSkinsController@updateForm')->name('opskins-update-form');
	Route::post('opskins-updater', 'OPSkinsController@updateFromData')->name('opskins-update-form-post');
});


/**
 * Token-Orders controller
 */
Route::group(['middleware' => ['auth', 'accepted']], function () {
	Route::post('token-orders/store', 'TokenOrderController@store')->name('token-orders.store')->middleware('can:create,App\Order');
	Route::get('token-orders/create', 'TokenOrderController@create')->name('token-orders.create')->middleware('can:create,App\Order');

	Route::get('token-orders/{order}', 'TokenOrderController@view')->name('token-orders.show')->middleware('can:view,order');
});


/**
 * Tokens controller
 */
Route::group(['middleware' => ['auth', 'accepted']], function () {
	Route::group(['middleware' => ['admin']], function () {
		Route::get('tokens/create', 'TokenController@create')->name('tokens.create')->middleware('can:create,App\Token');
		Route::post('tokens/create', 'TokenController@create')->name('tokens.create')->middleware('can:create,App\Token');
	});
	Route::post('tokens/extra', 'TokenController@storeExtra')->name('tokens.storeExtra')->middleware('can:create,App\Token');
	Route::get('tokens/{token}', 'TokenController@show')->name('tokens.show')->middleware('can:view,token');
	Route::get('tokens/{token}/edit', 'TokenController@edit')->name('tokens.edit')->middleware('can:edit,token');
	Route::patch('tokens/{token}/restore', 'TokenController@restore')->name('tokens.restore')->middleware('can:delete,token');
	Route::delete('tokens/{token}', 'TokenController@delete')->name('tokens.delete')->middleware('can:delete,token');
	Route::patch('tokens/{token}', 'TokenController@update')->name('tokens.update')->middleware('can:edit,token');
	Route::post('tokens', 'TokenController@store')->name('tokens.store')->middleware('can:create,App\Token');
	Route::get('tokens', 'TokenController@index')->name('tokens.index');
});


/**
 * Servers controller
 */
Route::group(['middleware' => ['auth', 'accepted', 'admin']], function () {

	Route::get('servers/{server}/edit', 'ServersController@edit')->name('servers.edit')->middleware('can:edit,server');
	Route::get('servers/create', 'ServersController@create')->name('servers.create')->middleware('can:create,App\Server');
	Route::get('servers', 'ServersController@index')->name('servers.index');
	Route::post('servers', 'ServersController@store')->name('servers.store')->middleware('can:create,App\Server');
	Route::patch('servers/{server}', 'ServersController@update')->name('servers.update')->middleware('can:update,server');
	Route::delete('servers/{server}', 'ServersController@delete')->name('servers.delete')->middleware('can:delete,server');
});


/**
 * Steam-Orders
 */
Route::group(['middleware' => ['accepted', 'auth']], function () {
	Route::group(['middleware' => ['tradelink', 'daemon']], function () {
		Route::post('steam-orders', 'SteamOrderController@store')->name('steam-orders.store');
		Route::get('steam-orders/create', 'SteamOrderController@create')->name('steam-orders.create')->middleware('can:create,App\Order');
		Route::get('steam-orders/{order}', 'SteamOrderController@show')->name('steam-orders.show')->middleware('can:view,order');
		Route::get('steam-orders/{order}/send-tradeoffer', 'SteamOrderController@sendTradeOffer')->name('steam-orders.send-tradeoffer')->middleware('can:view,order');
	});
});


/**
 * Users controller
 */
Route::group(['middleware' => ['auth']], function () {
	Route::get('/', 'UserController@home')->name('home');
	Route::get('accept', 'UserController@accept')->name('users.accept');
	Route::get('users/settings', 'UserController@settings')->name('users.settings');
	Route::post('users/settings', 'UserController@settingsUpdate')->name('users.settings.update');
	Route::patch('users/{user}/ban', 'UserController@ban')->name('users.ban')->middleware('can:ban,user');
	Route::patch('users/{user}/unban', 'UserController@unban')->name('users.unban')->middleware('can:unban,user');

	Route::group(['middleware' => ['admin']], function () {
		Route::get('/users', 'UserController@index')->name('users.index');
	});
});