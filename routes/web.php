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

Route::get('admins_simple', 'ConfirmationsController@generateAdminsSimple')->middleware(['daemon'])->name('admins-simple');
Route::get('create-confirmation/{public_id}', 'ConfirmationsController@createConfirmation')->middleware(['auth', 'daemon', 'accepted'])->name('create-confirmation');

Route::get('orders', 'OrdersController@view')->middleware('auth', 'accepted')->name('orders');

Route::get('daemon-login', 'DaemonController@login')->middleware('daemon.online')->name('daemon-login');
Route::post('daemon-login', 'DaemonController@loginPost')->middleware('daemon.online')->name('daemon-login-post');
Route::get('logs', 'DaemonController@logs')->middleware('daemon.online')->name('daemon-logs');
Route::get('stdout', 'DaemonController@stdout')->middleware('daemon.online')->name('daemon-stdout');
Route::get('stderr', 'DaemonController@stderr')->middleware('daemon.online')->name('daemon-stderr');
Route::get('kill', 'DaemonController@kill')->middleware('daemon.online')->name('daemon-kill');

Route::get('view-steam-order/{public_id}', 'SteamOrderController@viewSteamOrder')->middleware(['auth', 'daemon', 'accepted'])->name('view-steam-order');
Route::get('send-trade-order/{public_id}', 'SteamOrderController@sendTradeOffer')->middleware(['auth', 'daemon', 'accepted'])->name('send-trade-offer');

Route::get('create-steam-offer', 'SteamOrderController@createSteamOffer')->middleware(['auth', 'daemon', 'accepted']);
Route::get('inventory', 'SteamOrderController@inventoryView')->middleware(['auth', 'tradelink', 'daemon', 'accepted'])->name('inventory');

Route::get('/', 'UserController@home')->middleware('auth')->name('home');
Route::get('accept', 'UserController@accept')->middleware('auth')->name('accept');
Route::get('settings', 'UserController@settings')->middleware('auth')->name('settings');
Route::post('settings', 'UserController@settingsUpdate')->middleware('auth')->name('settings.update');
