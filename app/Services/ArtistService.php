<?php

namespace App\Services;

use App\Models\Artist;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ArtistService
{
    public function make(array $artistData): bool
    {
        $artist = new Artist();
        return $this->storeArtist($artist, $artistData);
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
        $artist->img = $this->storeImage($artistData['img']);

        return $artist->save();
    }

    private function storeImage(?UploadedFile $file): ?string
    {
        if (isset($file)) {
            $fileName = time() . '.' . $file->extension();
            $file->move(storage_path('app/images/artists'), $fileName);
            return $fileName;
        }
        return null;
    }
}
