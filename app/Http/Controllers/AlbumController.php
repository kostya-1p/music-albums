<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function showAllAlbums() {
        $albums = Album::all();
        return view('albums')->with('albums', $albums);
    }
}
