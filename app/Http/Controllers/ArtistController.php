<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtistRequest;
use App\Http\Requests\FilterRequest;
use App\Logging\ArtistLogger;
use App\Providers\RouteServiceProvider;
use App\Repositories\Interfaces\ArtistRepositoryInterface;
use App\Services\ArtistService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ArtistController extends Controller
{
    private ArtistRepositoryInterface $artistRepository;
    private ArtistService $artistService;
    private ArtistLogger $artistLogger;

    public function __construct(ArtistRepositoryInterface $artistRepository, ArtistService $artistService,
                                ArtistLogger              $artistLogger)
    {
        $this->artistRepository = $artistRepository;
        $this->artistService = $artistService;
        $this->artistLogger = $artistLogger;
    }

    public function index(): View
    {
        $artists = $this->artistRepository->getAllPaginated(5);
        return view('artists', compact('artists'));
    }

    public function indexFiltered(FilterRequest $request): View
    {
        $artists = $this->artistRepository->getFiltered($request->artist, 5);
        return view('artists', compact('artists'));
    }

    public function create(): View
    {
        return view('create-artist');
    }

    public function store(ArtistRequest $request): RedirectResponse
    {
        $this->artistService->make($request->validated());
        $this->artistLogger->logAddedArtist($request->validated());
        return redirect()->route('artists.index');
    }
}
