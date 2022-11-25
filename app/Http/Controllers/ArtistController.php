<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtistRequest;
use App\Http\Requests\DeleteAlbumRequest;
use App\Http\Requests\FilterRequest;
use App\Logging\ArtistLogger;
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
        $artistsForFilter = $this->artistRepository->getAllByUniqueNames();
        return view('artists', compact('artists', 'artistsForFilter'));
    }

    public function indexFiltered(FilterRequest $request): View
    {
        $artists = $this->artistRepository->getFiltered($request->artist, 5);
        $artistsForFilter = $this->artistRepository->getAllByUniqueNames();
        return view('artists', compact('artists', 'artistsForFilter'));
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

    public function edit(int $artistId): RedirectResponse|View
    {
        $artist = $this->artistRepository->getById($artistId);
        if (isset($artist)) {
            return view('create-artist', compact('artist'));
        }
        return redirect()->back();
    }

    public function update(ArtistRequest $request): RedirectResponse
    {
        $artist = $this->artistRepository->getById($request->id);
        $oldArtist = $this->artistRepository->getById($request->id);

        if (!isset($artist)) {
            $this->artistService->make($request->validated());
            $this->artistLogger->logAddedArtist($request->validated());
            return redirect()->route('artists.index');
        }

        $this->artistService->edit($artist, $request->validated());
        $this->artistLogger->logEditedArtist($oldArtist, $artist);

        return redirect()->route('artists.index');
    }

    public function destroy(DeleteAlbumRequest $request): RedirectResponse
    {
        $artist = $this->artistRepository->getById($request->id);
        if (!isset($artist)) {
            return redirect()->route('artists.index');
        }

        $this->artistLogger->logDeletedArtist($artist);
        $this->artistService->delete($artist);

        return redirect()->back();
    }
}
