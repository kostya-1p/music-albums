<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AlbumLastFmService
{
    private string $baseURL;
    private string $lastfmApiKey;
    private string $lastfmApiVersion;

    private const ARTISTS_KEY = 'artist';
    private const ALBUM_KEY = 'name';
    private const IMAGE_KEY = 'image';
    private const LARGE_IMAGE_INDEX = 2;

    public function __construct()
    {
        $this->baseURL = config('services.lastfm_api.domain');
        $this->lastfmApiKey = config('services.lastfm_api.api_key');
        $this->lastfmApiVersion = config('services.lastfm_api.api_version');
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

    public function loadAlbumInfo(string $albumName): array
    {
        $urlParams = array('album' => $albumName, 'format' => 'json');
        $url = $this->buildURL('album.search', $urlParams);

        $response = Http::get($url);
        $responseArray = $response->json();

        return $this->getAlbumInfoFromResponse($responseArray);
    }

    private function getAlbumInfoFromResponse(array $responseArray): array
    {
        $albumsInfo = [];
        $albums = $responseArray['results']['albummatches']['album'];

        foreach ($albums as $album) {
            $albumsInfo[$album[self::ARTISTS_KEY]] = [
                'album' => $album[self::ALBUM_KEY],
                'image' => $album[self::IMAGE_KEY][self::LARGE_IMAGE_INDEX]['#text'],
            ];
        }

        return $albumsInfo;
    }

    public function loadDescription(string $albumName, string $artistName): ?string
    {
        $urlParams = array('album' => $albumName, 'artist' => $artistName, 'format' => 'json');
        $url = $this->buildURL('album.getinfo', $urlParams);

        $response = Http::get($url);
        $responseArray = $response->json();

        if (array_key_exists('album', $responseArray) && array_key_exists('wiki', $responseArray['album'])) {
            return $responseArray['album']['wiki']['summary'];
        }

        return null;
    }
}
