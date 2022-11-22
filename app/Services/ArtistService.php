<?php

namespace App\Services;

use App\Models\Artist;
use Illuminate\Support\Facades\Storage;

class ArtistService
{
    public function make(array $artistData): Artist
    {
        $artist = new Artist();
        $this->storeArtist($artist, $artistData);
        return $artist;
    }

    public function edit(Artist $artist, array $artistData): bool
    {
        Storage::disk('images')->delete("artists/$artist->img");
        return $this->storeArtist($artist, $artistData);
    }

    public function delete(Artist $artist): bool
    {
        Storage::disk('images')->delete("artists/$artist->img");
        return $artist->delete();
    }

    private function storeArtist(Artist $artist, array $artistData): bool
    {
        $artist->name = $artistData['name'];
        $artist->img = $this->downloadImageToStorage($artistData['img']);

        return $artist->save();
    }

    private function downloadImageToStorage(?string $url): ?string
    {
        if (isset($url)) {
            $imageFile = file_get_contents($url);
            $imageInfo = pathinfo($url);
            $imageName = $imageInfo['basename'];
            Storage::disk('images')->put("artists/$imageName", $imageFile);
            return $imageName;
        }
        return null;
    }
}
