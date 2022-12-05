<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ArtistLastFmService
{
    private string $baseURL;
    private string $lastfmApiKey;
    private string $lastfmApiVersion;

    private const ARTIST_KEY = 'name';
    private const IMAGE_KEY = 'image';
    private const LARGE_IMAGE_INDEX = 2;

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

        foreach ($artists as $artist) {
            $artistsInfo[] = [
                self::ARTIST_KEY => $artist[self::ARTIST_KEY],
                self::IMAGE_KEY => $artist[self::IMAGE_KEY][self::LARGE_IMAGE_INDEX]['#text'],
            ];
        }

        return $artistsInfo;
    }
}
