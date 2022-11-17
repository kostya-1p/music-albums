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

    public function logEditedArtist(Artist $oldArtistData, array $newArtistData): void
    {
        Log::channel('artists_changes')->info('Artist edited', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'artist_id' => $oldArtistData->id,
            'old_artist_name' => $oldArtistData->name,
            'old_artist_img' => $oldArtistData->img,
            'new_artist_name' => $newArtistData['name'],
            'new_artist_img' => $newArtistData['img']]);
    }
}
