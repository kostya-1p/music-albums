<?php

namespace App\Services;

use App\Models\Artist;

class ArtistService
{
    public function make(array $artistData): bool
    {
        $artist = new Artist();
        return $this->storeAlbum($artist, $artistData);
    }

    private function storeAlbum(Artist $artist, array $artistData): bool
    {
        $artist->name = $artistData['name'];
        $artist->img = $artistData['img'];

        return $artist->save();
    }
}
