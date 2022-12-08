<?php

namespace App\Http\Controllers;

use App\Services\ArtistLastFmService;
use Illuminate\Http\JsonResponse;

class ArtistLastFmController extends Controller
{
    private ArtistLastFmService $artistLastFmService;

    public function __construct(ArtistLastFmService $artistLastFmService)
    {
        $this->artistLastFmService = $artistLastFmService;
    }

    public function index(string $artistName): JsonResponse
    {
        $artistInfo = $this->artistLastFmService->loadArtistInfo($artistName);
        return response()->json($artistInfo);
    }
}
