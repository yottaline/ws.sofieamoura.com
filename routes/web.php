<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::post('locations/load', 'LocationController@load');
Route::get('account/request', fn () => view('contents.profile.request'));
Route::middleware('auth')->group(function () {
    Route::get('/', fn () => view('home'));

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
        Route::get('view/{id}', 'WsOrderController@view');
    });
    Route::prefix('profile')->group(function () {
        Route::get('/', 'ProfileController@index');
        Route::put('update', 'ProfileController@update');
        Route::get('orders', 'WsOrderController@index');
        Route::get('order/{id}', 'WsOrderController@view');
    });
});


Route::post('test', 'RetailerController@submitForgetPasswordForm')->name('forget.password.get');
Route::get('reset-password/{token}', 'RetailerController@showResetPasswordForm')->name('reset.password.get');


// Route::post('register_retailer', 'RetailerController@submit');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
