<?php

namespace App\Services;

use App\Models\Artist;
use Illuminate\Support\Facades\Storage;

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

        $imageFile = file_get_contents($artistData['img']);
        $imageName = substr($artistData['img'], strrpos($artistData['img'], '/') + 1);
        Storage::disk('images')->put("artists/$imageName", $imageFile);
        $artist->img = $imageName;

        return $artist->save();
    }
}
