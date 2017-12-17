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
});

Route::get('login', 'AuthController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('dashboard', function() {
   return 'Dashboard';
})->name('dashboard');

Route::get('debug-form', 'SteamOrderController@debugForm');

Route::get('settings', function() {
   return 'Settings';
})->name('settings');

Route::get('/inventory', 'SteamOrderController@inventoryView')->middleware('auth')->name('inventory');

Route::get('/create-steam-offer', 'SteamOrderController@createSteamOffer')->middleware('auth');
Route::get('/view-steam-offer/{public_id}', 'SteamOrderController@viewSteamOffer')->middleware('auth')->name('view-steam-offer');

Route::get('/send-trade-offer/{public_id}', 'SteamOrderController@sendTradeOffer')->middleware('auth')->name('send-trade-offer');

Route::get('refresh-opskins-cache', 'OPSkinsController@refreshOPSkinsCache');

Route::get('settings', 'UserController@settings')->name('settings');
Route::post('settings', 'UserController@settingsUpdate')->name('settings.update');
