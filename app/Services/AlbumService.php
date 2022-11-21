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
        return $this->storeAlbum($album, $albumData, $artistId);
    }

    public function delete(Album $album): bool
    {
        return $album->delete();
    }

    private function storeAlbum(Album $album, array $albumData, int $artistId): bool
    {
        $album->name = $albumData['name'];
        $album->artist_id = $artistId;
        $album->description = $albumData['description'];

        $imageFile = file_get_contents($albumData['img']);
        $imageName = substr($albumData['img'], strrpos($albumData['img'], '/') + 1);
        Storage::disk('images')->put("albums/$imageName", $imageFile);
        $album->img = $imageName;

        return $album->save();
    }
}
