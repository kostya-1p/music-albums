<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ArtistLastFmService
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

    public function loadArtistInfo(string $artistName): array
    {
        $urlParams = array('artist' => $artistName, 'format' => 'json');
        $url = $this->buildURL('artist.search', $urlParams);

        $response = Http::get($url);
        $responseArray = $response->json();

        return $this->getArtistsInfoFromResponse($responseArray);
    }

    private function getArtistsInfoFromResponse(array $responseArray): array
    {
        $artistsInfo = [];
        $artists = $responseArray['results']['artistmatches']['artist'];

        $artistKey = 'name';
        $imageKey = 'image';
        $largeImageIndex = 2;

        foreach ($artists as $artist) {
            $artistsInfo[] = array($artistKey => $artist[$artistKey], $imageKey => $artist[$imageKey][$largeImageIndex]['#text']);
        }

        return $artistsInfo;
    }
}
