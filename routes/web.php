<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::post('locations/load', 'LocationController@load');
Route::get('account/request', fn () => view('contents.profile.request'));
Route::middleware('auth')->group(function () {
    Route::get('/', 'HomeController@index');

    Route::prefix('products')->group(function () {
        Route::post('/', 'WsProductController@index');
        Route::post('load', 'WsProductController@load');
        Route::post('sizes', 'WsProductsSizeController@load');
        Route::post('media', 'ProductsMediaController@load');
        Route::post('sizes_and_media', 'WsProductController@sizesAndMedia');
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', 'WsOrderController@index');
        Route::post('load', 'WsOrderController@load');
        Route::post('submit', 'WsOrderController@submit');
        Route::get('view/{code}', 'WsOrderController@view');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', 'ProfileController@index');
        Route::put('update', 'ProfileController@update');
        Route::put('update_address', 'ProfileController@updateAddress');
    });
});


// Route::get('test', 'ProfileController@test');
// Route::post('register_retailer', 'RetailerController@submit');
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
