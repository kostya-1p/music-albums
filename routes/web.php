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

Route::get('/album/edit/{id}', [\App\Http\Controllers\AlbumController::class, 'getEditPage'])->
name('editAlbumPage')->middleware('auth')->whereNumber('id');

Route::post('/album/create', [\App\Http\Controllers\AlbumController::class, 'addAlbum'])->
name('addAlbum')->middleware('auth');

Route::post('/album/edit', [\App\Http\Controllers\AlbumController::class, 'editAlbum'])->
name('editAlbum')->middleware('auth');

Route::post('/album/delete', [\App\Http\Controllers\AlbumController::class, 'deleteAlbum'])->
name('deleteAlbum')->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/search/{albumName}', [\App\Http\Controllers\AlbumController::class, 'searchAlbumByName'])->name('search');

require __DIR__ . '/auth.php';
