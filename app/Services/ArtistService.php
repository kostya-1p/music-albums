<?php

namespace App\Services;

use App\Models\Artist;

class ArtistService
{
    public function make(array $artistData): Artist
    {
        $artist = new Artist();
        $this->storeAlbum($artist, $artistData);
        return $artist;
    }

    public function edit(Artist $artist, array $artistData): bool
    {
        return $this->storeAlbum($artist, $artistData);
    }

    public function delete(Artist $artist): bool
    {
        return $artist->delete();
    }

    private function storeAlbum(Artist $artist, array $artistData): bool
    {
        $artist->name = $artistData['name'];
        $artist->img = $artistData['img'];

        return $artist->save();
    }
}
