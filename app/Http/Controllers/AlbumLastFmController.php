<?php

namespace App\Http\Controllers;

use App\Services\AlbumLastFmService;

class AlbumLastFmController extends Controller
{
    private AlbumLastFmService $albumLastFmService;
    private string $apiKey;

    public function __construct(AlbumLastFmService $albumLastFmService)
    {
        $this->albumLastFmService = $albumLastFmService;
        $this->apiKey = env('API_KEY');
    }

    public function index(string $albumName)
    {
        $artistsAndImages = $this->albumLastFmService->loadArtistsAndImages($this->apiKey, $albumName);
        return json_encode($artistsAndImages);
    }

    public function indexDescription(string $albumName, string $artistName)
    {
        $description = $this->albumLastFmService->loadDescription($this->apiKey, $albumName, $artistName);
        return json_encode(['description' => $description]);
    }
}
