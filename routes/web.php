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
Route::get('create-confirmation/{public_id}', 'ConfirmationsController@createConfirmation')->middleware(['auth', 'daemon', 'accepted'])->name('create-confirmation');

Route::get('orders', 'OrdersController@view')->middleware('auth', 'accepted')->name('orders');
Route::get('confirmations', 'ConfirmationsController@view')->middleware('auth', 'accepted')->name('confirmations');
Route::get('sync-server', 'ConfirmationsController@syncServer')->middleware('auth', 'admin')->name('sync-server');

Route::group(['middleware' => ['admin', 'daemon.online']], function () {
    Route::get('laravel-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('laravel-logs');
    Route::get('logs', 'DaemonController@logs')->name('daemon-logs');
    Route::get('stdout', 'DaemonController@stdout')->name('daemon-stdout');
    Route::get('stderr', 'DaemonController@stderr')->name('daemon-stderr');
    Route::get('kill', 'DaemonController@kill')->name('daemon-kill');

    Route::get('daemon-login', 'DaemonController@login')->name('daemon-login');
    Route::post('daemon-login', 'DaemonController@loginPost')->name('daemon-login-post');
});

Route::group(['middleware' => ['admin']], function () {
    Route::get('opskins_updater', 'OPSkinsController@updateForm')->name('opskins-update-form');
    Route::post('opskins_updater', 'OPSkinsController@updateFromData')->name('opskins-update-form-post');
});

Route::group(['middleware' => ['auth', 'daemon', 'accepted']], function () {
    Route::get('view-steam-order/{public_id}', 'SteamOrderController@viewSteamOrder')->name('view-steam-order');
    Route::get('send-trade-order/{public_id}', 'SteamOrderController@sendTradeOffer')->name('send-trade-offer');
});

Route::group(['middleware' => ['auth', 'accepted']], function () {
    Route::get('token-order-preview', 'TokenOrderController@tokenOrderPreview')->name('token-order-preview');
    Route::get('token-generation', 'TokenOrderController@tokenGeneration')->name('token-generation');
    Route::post('token-generation', 'TokenOrderController@tokenGenerationPost')->name('token-generation');
    Route::post('token-generate', 'TokenOrderController@tokenGenerate')->name('token-generate');
    Route::get('view-token/{token}', 'TokenOrderController@viewToken')->name('view-token');
    Route::get('token', 'TokenOrderController@tokenView')->name('token');
    Route::post('create-token-order', 'TokenOrderController@createTokenOrder')->name('create-token-order');
    Route::get('view-token-order/{public_id}', 'TokenOrderController@view')->name('view-token-order');
});

Route::group(['middleware' => ['admin']], function () {
    Route::get('tokens', 'TokenOrderController@listTokens')->name('tokens');
});

Route::group(['middleware' => ['auth', 'tradelink', 'daemon', 'accepted']], function () {
    Route::get('create-steam-offer', 'SteamOrderController@createSteamOffer')->name('create-steam-order');
    Route::get('inventory', 'SteamOrderController@inventoryView')->name('inventory');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'UserController@home')->name('home');
    Route::get('accept', 'UserController@accept')->name('accept');
    Route::get('settings', 'UserController@settings')->name('settings');
    Route::post('settings', 'UserController@settingsUpdate')->name('settings.update');
});
