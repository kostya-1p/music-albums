<?php

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

Route::get('/', [\App\Http\Controllers\AlbumController::class, 'showAllAlbums'])->
name('albums');

Route::get('/album/create', [\App\Http\Controllers\AlbumController::class, 'getCreatePage'])->
name('createAlbumPage')->middleware('auth');

Route::post('/album/create', [\App\Http\Controllers\AlbumController::class, 'addAlbum'])->
name('addAlbum')->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
