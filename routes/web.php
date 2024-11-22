<?php

use App\Http\Controllers\Website\ApiController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\PalleteController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::middleware(['assign.identifier'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('website-home');
    // Route::get('/{?}', [HomeController::class, 'search'])->name('website-search');
    Route::post('/like', [HomeController::class, 'like'])->name('website-palette-like');
    // Route::post('/like',[HomeController::class,'like'])->name('website-palette-like');

    Route::controller(ApiController::class)
        ->prefix('api')
        ->group(function () {
            Route::get('collection', 'collection')->name('website-collection-api');
        });

    Route::controller(PalleteController::class)
        ->prefix('pallete')
        ->group(function () {
            Route::match(['get', 'post'], 'create', 'create')->name('website-palette-create');
            Route::get('view/{id}', 'view')->name('website-pallete-view'); // Accept `id` parameter
            Route::get('single/{id}', 'single')->name('website-palette-single'); // Accept `id` parameter
            Route::get('collection', 'collection')->name('website-pallete-collection'); // Accept `id` parameter
        });
});
