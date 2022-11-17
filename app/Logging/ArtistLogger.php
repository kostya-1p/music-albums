<?php

namespace App\Logging;

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
}
