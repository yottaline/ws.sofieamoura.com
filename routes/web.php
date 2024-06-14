<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function(){
    // settings route
    Route::get('settings', 'LocationController@index');

    Route::prefix('ws_products')->group(function(){
        Route::get('/', 'WsProductController@index');
        Route::post('load', 'WsProductController@load');
        Route::get('view/{ref}', 'WsProductController@view');
    });

    Route::get('profile', 'ProfileController@edit');
    Route::put('update', 'ProfileController@update');
});
Route::prefix('locations')->group(function(){
    Route::post('load', 'LocationController@load');
});
// Route::post('register_retailer', 'RetailerController@submit');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';