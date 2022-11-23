<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AlbumLastFmService
{
    public function loadArtistsInfo(string $apiKey, string $albumName): array
    {
        $response = Http::get("http://ws.audioscrobbler.com/2.0/?method=album.search&album=
        {$albumName}&api_key={$apiKey}&format=json");

        $responseArray = $response->json();

        return $this->getArtistsInfoFromResponse($responseArray);
    }

    private function getArtistsInfoFromResponse(array $responseArray): array
    {
        $artistsInfo = [];
        $albums = $responseArray['results']['albummatches']['album'];

        $artistsKey = 'artist';
        $albumKey = 'name';
        $imageKey = 'image';
        $largeImageIndex = 2;

        foreach ($albums as $album) {
            $artistsInfo[$album[$artistsKey]] = array('album' => $album[$albumKey], 'image' => $album[$imageKey][$largeImageIndex]['#text']);
        }

        return $artistsInfo;
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
