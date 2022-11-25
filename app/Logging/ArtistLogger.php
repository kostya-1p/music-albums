<?php

namespace App\Logging;

use App\Models\Artist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ArtistLogger
{
    public function logAddedArtist(array $artistData): void
    {
        Log::channel('artists_changes')->info('Artist added', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'artist_name' => $artistData['name'],
            'artist_image' => $artistData['img']]);
    }

    public function logEditedArtist(Artist $oldArtist, Artist $newArtist): void
    {
        Log::channel('artists_changes')->info('Artist edited', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'artist_id' => $oldArtist->id,
            'old_artist_name' => $oldArtist->name,
            'old_artist_img' => $oldArtist->img,
            'new_artist_name' => $newArtist->name,
            'new_artist_img' => $newArtist->img]);
    }

    public function logDeletedArtist(Artist $artist): void
    {
        Log::channel('artists_changes')->info('Artist deleted', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'artist_id' => $artist->id,
            'artist_name' => $artist->name]);
    }
}
