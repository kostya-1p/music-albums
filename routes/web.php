<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AlbumController;

Route::group(['controller' => AlbumController::class, 'middleware' => 'auth', 'prefix' => 'albums'], function () {
    Route::get('/create', 'create')->name('albums.create');
    Route::get('/edit/{id}', 'edit')->name('albums.edit')->whereNumber('id');
    Route::post('/create', 'store')->name('albums.store');
    Route::post('/edit/{id}', 'update')->name('albums.update')->whereNumber('id');
    Route::post('/delete/{id}', 'destroy')->name('albums.destroy')->whereNumber('id');
});

Route::controller(AlbumController::class)->group(function () {
    Route::get('/albums', 'index')->name('albums.index');
    Route::get('/', function () {
        return redirect(route('albums.index'));
    });
});

require __DIR__ . '/auth.php';
