<?php

use App\Http\Controllers\ArtistController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AlbumController;

Route::group(['controller' => AlbumController::class, 'middleware' => 'auth', 'prefix' => 'albums'], function () {
    Route::get('/albums/create', 'create')->name('albums.create');
    Route::get('/albums/edit/{id}', 'edit')->name('albums.edit')->whereNumber('id');
    Route::post('/albums/create', 'store')->name('albums.store');
    Route::put('/albums/edit/{id}', 'update')->name('albums.update')->whereNumber('id');
    Route::delete('/albums/delete/{id}', 'destroy')->name('albums.destroy')->whereNumber('id');
});

Route::controller(AlbumController::class)->group(function () {
    Route::get('/albums', 'index')->name('albums.index');
    Route::get('/albums/filter/', 'indexFiltered')->name('albums.indexFiltered');
    Route::get('/', function () {
        return redirect(route('albums.index'));
    });
});

Route::controller(ArtistController::class)->group(function () {
    Route::get('/artists', 'index')->name('artists.index');
    Route::get('/artists/filter', 'indexFiltered')->name('artists.indexFiltered');
});

Route::controller(AlbumController::class)->group(function () {
    Route::get('/search/{albumName}', 'searchAlbumByName')->name('search');
    Route::get('/search_description/{albumName}/{artistName}', 'getAlbumDescription')->
    name('albumInfo');
});

require __DIR__ . '/auth.php';
