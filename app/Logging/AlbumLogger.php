<?php

namespace App\Logging;

use App\Models\Album;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AlbumLogger
{
    public function logAddedAlbum(array $albumData): void
    {
        Log::channel('albums_changes')->info('Album added', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'album_name' => $albumData['name'],
            'album_artist' => $albumData['artist']]);
    }

    public function logEditedAlbum(Album $oldAlbum, Album $newAlbum): void
    {
        Log::channel('albums_changes')->info('Album edited', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'album_id' => $oldAlbum->id,
            'old_album_name' => $oldAlbum->name,
            'old_album_artist' => $oldAlbum->artist->name,
            'old_album_img' => $oldAlbum->img,
            'new_album_name' => $newAlbum->name,
            'new_album_artist' => $newAlbum->artist->name,
            'new_album_img' => $newAlbum->img]);
    }

    public function logDeletedAlbum(Album $album): void
    {
        Log::channel('albums_changes')->info('Album deleted', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'album_id' => $album->id,
            'album_name' => $album->name,
            'album_artist_id' => $album->artist_id]);
    }
}
