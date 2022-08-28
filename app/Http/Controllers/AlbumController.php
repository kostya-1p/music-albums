<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $oldAlbumData = clone $album;
        $isEditing = isset($album->id);

        if ($this->isValidImageURL($request->img)) {
            Album::storeAlbum($album, $request->name, $request->artist, $request->description, $request->img);
            $this->logAlbumChanges($isEditing, $oldAlbumData, $album);
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

    private function logAlbumChanges(bool $isEditing, Album $oldAlbumData, Album $album) {
        if ($isEditing) {
            $this->logEditedAlbum($oldAlbumData, $album);
            return;
        }

        $this->logAddedAlbum($album);
    }

    private function logAddedAlbum(Album $album) {
        Log::info('Album added', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'album_id' => $album->id,
            'album_name' => $album->name,
            'album_artist' => $album->artist]);
    }

    private function logEditedAlbum(Album $oldAlbumData, Album $newAlbumData) {
        Log::info('Album edited', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'album_id' => $oldAlbumData->id,
            'old_album_name' => $oldAlbumData->name,
            'old_album_artist' => $oldAlbumData->artist,
            'old_album_img' => $oldAlbumData->img,
            'new_album_name' => $newAlbumData->name,
            'new_album_artist' => $newAlbumData->artist,
            'new_album_img' => $newAlbumData->img,]);
    }
}
