<?php

namespace App\Services;

use App\Models\Album;
use Illuminate\Support\Facades\Storage;

class AlbumService
{
    public function make(array $albumData, int $artistId): bool
    {
        $album = new Album();
        return $this->storeAlbum($album, $albumData, $artistId);
    }

    public function edit(Album $album, array $albumData, int $artistId): bool
    {
        Storage::disk('images')->delete("albums/$album->img");
        return $this->storeAlbum($album, $albumData, $artistId);
    }

    public function delete(Album $album): bool
    {
        Storage::disk('images')->delete("albums/$album->img");
        return $album->delete();
    }

    private function storeAlbum(Album $album, array $albumData, int $artistId): bool
    {
        $album->name = $albumData['name'];
        $album->artist_id = $artistId;
        $album->description = $albumData['description'];
        $album->img = $this->downloadImageToStorage($albumData['img']);

        return $album->save();
    }

    private function downloadImageToStorage(string $url): string
    {
        $imageFile = file_get_contents($url);
        $imageInfo = pathinfo($url);
        $imageName = $imageInfo['basename'];
        Storage::disk('images')->put("albums/$imageName", $imageFile);
        return $imageName;
    }
}
