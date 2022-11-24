<?php

namespace App\Http\Controllers;

use App\Services\ArtistLastFmService;
use Illuminate\Http\JsonResponse;

class ArtistLastFmController extends Controller
{
    private ArtistLastFmService $artistLastFmService;

    public function __construct()
    {
        $this->artistLastFmService = new ArtistLastFmService(config('services.lastfm_api.domain'),
            config('services.lastfm_api.api_key'), config('services.lastfm_api.api_version'));
    }

    public function index(string $artistName): JsonResponse
    {
        $artistInfo = $this->artistLastFmService->loadArtistInfo($artistName);
        return response()->json($artistInfo);
    }
}
