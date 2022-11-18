<?php

namespace App\Services;

use App\Models\Album;

class AlbumService
{
    public function make(array $albumData, int $artistId): bool
    {
        $album = new Album();
        return $this->storeAlbum($album, $albumData, $artistId);
    }

    public function edit(Album $album, array $albumData): bool
    {
        return $this->storeAlbum($album, $albumData);
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
        $album->img = $albumData['img'];

        return $album->save();
    }
}
