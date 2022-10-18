<?php

namespace App\Services;

use App\Models\Album;

class AlbumService
{
    public function make(array $albumData): bool
    {
        $album = new Album();
        return $this->storeAlbum($album, $albumData);
    }

    public function edit(Album $album, array $albumData): bool
    {
        return $this->storeAlbum($album, $albumData);
    }

    public function delete(Album $album): bool
    {
        return $album->delete();
    }

    private function storeAlbum(Album $album, array $albumData): bool
    {
        $album->name = $albumData['name'];
        $album->artist = $albumData['artist'];
        $album->description = $albumData['description'];
        $album->img = $albumData['img'];

        return $album->save();
    }

    public function isValidImageURL(string $img): bool
    {
        if (filter_var($img, FILTER_VALIDATE_URL)) {
            $headers = get_headers($img, 1);
            if (strpos($headers['Content-Type'], 'image/') !== false) {
                return true;
            }
        }

        return false;
    }
}