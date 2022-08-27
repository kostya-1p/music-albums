<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function showAllAlbums() {
        $albums = Album::all();
        return view('albums')->with('albums', $albums);
    }

    public function getCreatePage() {
        return view('create-album');
    }

    public function getEditPage(int $albumId) {
        $album = Album::find($albumId);
        return view('create-album')->with('album', $album);
    }

    public function addAlbum(Request $request) {
        $request->validate([
            'name' => ['required', 'string'],
            'artist' => ['required', 'string'],
            'img' => ['required', 'string']
        ]);

        if (filter_var($request->img, FILTER_VALIDATE_URL)) {
            $headers = get_headers($request->img, 1);
            if (strpos($headers['Content-Type'], 'image/') !== false) {
                Album::addAlbum($request->name, $request->artist, $request->description, $request->img);
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
