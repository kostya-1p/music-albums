<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AlbumLastFmService
{
    public function loadArtistsAndImages(string $apiKey, string $albumName): array
    {
        $response = Http::get("http://ws.audioscrobbler.com/2.0/?method=album.search&album=
        {$albumName}&api_key={$apiKey}&format=json");

        $responseArray = $response->json();

        $artistsAndImages = [];
        $artistsAndImages['artists'] = $this->getArrayOfArtists($responseArray);
        $artistsAndImages['images'] = $this->getArrayOfImages($responseArray);

        return $artistsAndImages;
    }

    private function getArrayOfArtists(array $responseArray): array
    {
        $artistsArray = $this->getArrayFromResponse($responseArray, 'artist');
        $uniqueArtists = array_unique($artistsArray);
        return array_values($uniqueArtists);
    }

    private function getArrayOfImages(array $responseArray): array
    {
        $imagesFullArray = $this->getArrayFromResponse($responseArray, 'image');
        $largeImages = [];
        $indexLargeImages = 2;

        foreach ($imagesFullArray as $image) {
            $largeImages[] = $image[$indexLargeImages]['#text'];
        }

        return $largeImages;
    }

    private function getArrayFromResponse(array $responseArray, string $key): array
    {
        $array = [];
        $albums = $responseArray['results']['albummatches']['album'];

        foreach ($albums as $album) {
            $array[] = $album[$key];
        }

        return $array;
    }

    public function loadDescription(string $apiKey, string $albumName, string $artistName): string
    {
        $response = Http::get("http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=
        {$apiKey}&artist={$artistName}&album={$albumName}&format=json");

        $responseArray = $response->json();
        $description = '';

        if (array_key_exists('album', $responseArray) && array_key_exists('wiki', $responseArray['album'])) {
            $description = $responseArray['album']['wiki']['summary'];
        }

        return $description;
    }
}
