<?php

namespace App\Services;

use App\Models\Album;
use Illuminate\Http\UploadedFile;
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
        $album->img = $this->storeImage($albumData['img']);

        return $album->save();
    }

    private function storeImage(UploadedFile $file): string
    {
        $fileName = time() . '.' . $file->extension();
        $file->move(storage_path('app/images/albums'), $fileName);
        return $fileName;
    }
}
