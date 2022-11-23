<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AlbumLastFmService
{
    private string $baseURL;
    private string $lastfmApiKey;
    private string $lastfmApiVersion;

    public function __construct(string $baseURL, string $lastfmApiKey, string $lastfmApiVersion)
    {
        $this->baseURL = $baseURL;
        $this->lastfmApiKey = $lastfmApiKey;
        $this->lastfmApiVersion = $lastfmApiVersion;
    }

    private function buildURL(string $method, array $params): string
    {
        $url = $this->baseURL . '/' . $this->lastfmApiVersion . "/?api_key=$this->lastfmApiKey";
        $url .= "&method=$method";

        foreach ($params as $paramKey => $paramValue) {
            $url .= "&$paramKey=$paramValue";
        }
        return $url;
    }

    public function loadArtistsInfo(string $albumName): array
    {
        $urlParams = array('album' => $albumName, 'format' => 'json');
        $url = $this->buildURL('album.search', $urlParams);

        $response = Http::get($url);
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

    public function loadDescription(string $albumName, string $artistName): string
    {
        $urlParams = array('album' => $albumName, 'artist' => $artistName, 'format' => 'json');
        $url = $this->buildURL('album.getinfo', $urlParams);

        $response = Http::get($url);
        $responseArray = $response->json();
        $description = '';

        if (array_key_exists('album', $responseArray) && array_key_exists('wiki', $responseArray['album'])) {
            $description = $responseArray['album']['wiki']['summary'];
        }

        return $description;
    }
}
