<?php

use App\Http\Controllers\ArtistController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AlbumController;

Route::group(['controller' => AlbumController::class, 'middleware' => 'auth', 'prefix' => 'albums'], function () {
    Route::get('/create', 'create')->name('albums.create');
    Route::get('/edit/{id}', 'edit')->name('albums.edit')->whereNumber('id');
    Route::post('/create', 'store')->name('albums.store');
    Route::put('/edit/{id}', 'update')->name('albums.update')->whereNumber('id');
    Route::delete('/delete/{id}', 'destroy')->name('albums.destroy')->whereNumber('id');
});

Route::controller(AlbumController::class)->group(function () {
    Route::get('/albums', 'index')->name('albums.index');
    Route::get('/albums/filter/', 'indexFiltered')->name('albums.indexFiltered');
    Route::get('/', function () {
        return redirect(route('albums.index'));
    });
});

Route::group(['controller' => ArtistController::class, 'middleware' => 'auth', 'prefix' => 'artists'], function () {
    Route::get('/create', 'create')->name('artists.create');
    Route::get('/edit/{id}', 'edit')->name('artists.edit')->whereNumber('id');
    Route::post('/create', 'store')->name('artists.store');
    Route::put('/edit/{id}', 'update')->name('artists.update')->whereNumber('id');
    Route::delete('/delete/{id}', 'destroy')->name('artists.destroy')->whereNumber('id');
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
