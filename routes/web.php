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
   return 'Dashboard';
})->name('dashboard');

Route::get('debug-form', 'SteamOrderController@debugForm');
Route::get('orders', 'OrdersController@view')->middleware('auth')->name('orders');


Route::get('online', function () {
    if(\App\Http\Controllers\DaemonController::isOnline()) {
        return 'Online and running...';
    } else {
        return 'Daemon is offline or non-responsive.';
    }
});

Route::get('logged', function () {
    if(\App\Http\Controllers\DaemonController::isLoggedIn()) {
        return 'Logged on Steam Servers';
    } else {
        return 'Waiting for authentication code...';
    }
});


Route::get('create-confirmation/{public_id}', 'ConfirmationsController@createConfirmation')->middleware(['auth', 'daemon'])->name('create-confirmation');
Route::get('view-confirmation/{public_id}', 'ConfirmationsController@viewConfirmation')->middleware(['auth'])->name('view-confirmation');
Route::get('admins_simple', 'ConfirmationsController@generateAdminsSimple')->middleware(['daemon'])->name('admins-simple');

Route::get('inventory', 'SteamOrderController@inventoryView')->middleware(['auth', 'daemon'])->name('inventory');

Route::get('create-steam-offer', 'SteamOrderController@createSteamOffer')->middleware(['auth', 'daemon']);
Route::get('view-steam-offer/{public_id}', 'SteamOrderController@viewSteamOffer')->middleware(['auth', 'daemon'])->name('view-steam-offer');

Route::get('send-trade-offer/{public_id}', 'SteamOrderController@sendTradeOffer')->middleware(['auth', 'daemon'])->name('send-trade-offer');

Route::get('refresh-opskins-cache', 'OPSkinsController@refreshOPSkinsCache');

Route::get('settings', 'UserController@settings')->middleware('auth')->name('settings');
Route::post('settings', 'UserController@settingsUpdate')->middleware('auth')->name('settings.update');
