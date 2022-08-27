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
        $album = new Album();
        return $this->storeAlbum($request, $album);
    }

    public function editAlbum(Request $request) {
        $album = Album::find($request->id);
        return $this->storeAlbum($request, $album);
    }

    private function storeAlbum(Request $request, Album $album) {
        $this->validateAlbumData($request);

        if ($this->isValidImageURL($request->img)) {
            Album::storeAlbum($album, $request->name, $request->artist, $request->description, $request->img);
            return redirect(RouteServiceProvider::HOME);
        }

        return redirect(RouteServiceProvider::HOME);
    }

    private function isValidImageURL(string $img) {
        if (filter_var($img, FILTER_VALIDATE_URL)) {
            $headers = get_headers($img, 1);
            if (strpos($headers['Content-Type'], 'image/') !== false) {
                return true;
            }
        }

        return false;
    }

    private function validateAlbumData(Request $request) {
        $request->validate([
            'name' => ['required', 'string'],
            'artist' => ['required', 'string'],
            'img' => ['required', 'string']
        ]);
    }
}
