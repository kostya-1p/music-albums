<?php

use App\Http\Controllers\AlbumController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(\App\Http\Controllers\AlbumLastFmController::class)->group(function () {
    Route::get('/album_lastfm/{albumName}', 'index')->name('album_lastfm.index');
    Route::get('/album_lastfm/description/{albumName}/{artistName}', 'indexDescription')->
    name('album_lastfm.index_description');
});
