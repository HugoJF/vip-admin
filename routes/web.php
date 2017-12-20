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

Route::get('/', function () {
    return view('welcome');
})->middleware('auth')->name('home');


Route::get('login', 'AuthController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');


Route::get('dashboard', function() {
    return redirect()->route('home');
})->name('dashboard');


Route::get('create-steam-offer', 'SteamOrderController@createSteamOffer')->middleware(['auth', 'daemon']);

// this should be debug only
Route::get('admins_simple', 'ConfirmationsController@generateAdminsSimple')->middleware(['daemon'])->name('admins-simple');

// Should be removed, its now a command
Route::get('refresh-opskins-cache', 'OPSkinsController@refreshOPSkinsCache');

// Should be updated to a nicer version
Route::get('orders', 'OrdersController@view')->middleware('auth')->name('orders');


Route::get('daemon-login', 'DaemonController@login')->middleware('daemon.online')->name('daemon-login');
Route::post('daemon-login', 'DaemonController@loginPost')->middleware('daemon.online')->name('daemon-login-post');


Route::get('create-confirmation/{public_id}', 'ConfirmationsController@createConfirmation')->middleware(['auth', 'daemon'])->name('create-confirmation');


Route::get('view-steam-offer/{public_id}', 'SteamOrderController@viewSteamOffer')->middleware(['auth', 'daemon'])->name('view-steam-offer');
Route::get('send-trade-offer/{public_id}', 'SteamOrderController@sendTradeOffer')->middleware(['auth', 'daemon'])->name('send-trade-offer');


Route::get('inventory', 'SteamOrderController@inventoryView')->middleware(['auth','tradelink', 'daemon'])->name('inventory');


Route::get('settings', 'UserController@settings')->middleware('auth')->name('settings');
Route::post('settings', 'UserController@settingsUpdate')->middleware('auth')->name('settings.update');
