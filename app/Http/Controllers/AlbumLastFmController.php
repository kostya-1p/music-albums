<?php

namespace App\Http\Controllers;

use App\Services\AlbumLastFmService;
use Illuminate\Http\JsonResponse;

class AlbumLastFmController extends Controller
{
    private AlbumLastFmService $albumLastFmService;

    public function __construct(AlbumLastFmService $albumLastFmService)
    {
        $this->albumLastFmService = $albumLastFmService;
    }

    public function index(string $albumName): JsonResponse
    {
        $artistsAndImages = $this->albumLastFmService->loadArtistsInfo($albumName);
        return response()->json($artistsAndImages);
    }

    public function indexDescription(string $albumName, string $artistName): JsonResponse
    {
        $description = $this->albumLastFmService->loadDescription($albumName, $artistName);
        if (empty($description)) {
            response()->json(['description' => $description], 204);
        }
        return response()->json(['description' => $description]);
    }
}
