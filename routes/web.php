<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AlbumController;

Route::group(['controller' => AlbumController::class, 'middleware' => 'auth', 'prefix' => 'album'], function () {
    Route::get('/create', 'getCreatePage')->name('createAlbumPage');
    Route::get('/edit/{id}', 'getEditPage')->name('editAlbumPage')->whereNumber('id');
    Route::post('/create', 'addAlbum')->name('addAlbum');
    Route::post('/edit', 'editAlbum')->name('editAlbum');
    Route::post('/delete', 'deleteAlbum')->name('deleteAlbum');
});

Route::controller(AlbumController::class)->group(function () {
    Route::get('/', 'showAllAlbums')->name('albums');
    Route::get('/search/{albumName}', 'searchAlbumByName')->name('search');
    Route::get('/search_description/{albumName}/{artistName}', 'getAlbumDescription')->
    name('albumInfo');
});

require __DIR__ . '/auth.php';
