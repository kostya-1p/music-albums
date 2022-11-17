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
