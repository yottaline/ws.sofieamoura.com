<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('locations/load', 'LocationController@load');
Route::get('account/request', fn () => view('contents.profile.request'));
Route::middleware('auth')->group(function () {
    Route::get('/', fn () => view('home'));

    Route::prefix('products')->group(function () {
        Route::get('/', 'WsProductController@index');
        Route::post('load', 'WsProductController@load');
        Route::get('view/{ref}', 'WsProductController@view');
    });

    Route::get('profile', 'ProfileController@edit');
    Route::put('update', 'ProfileController@update');
});

// Route::post('register_retailer', 'RetailerController@submit');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';