<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Http\Requests\DeleteAlbumRequest;
use App\Http\Requests\FilterRequest;
use App\Logging\AlbumLogger;
use App\Providers\RouteServiceProvider;
use App\Repositories\Interfaces\AlbumRepositoryInterface;
use App\Repositories\Interfaces\ArtistRepositoryInterface;
use App\Services\AlbumService;
use App\Services\ArtistService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AlbumController extends Controller
{
    private AlbumRepositoryInterface $albumRepository;
    private AlbumService $albumService;
    private AlbumLogger $albumLogger;
    private ArtistRepositoryInterface $artistRepository;
    private ArtistService $artistService;

    public function __construct(AlbumRepositoryInterface $albumRepository, AlbumService $albumService,
                                AlbumLogger              $albumLogger, ArtistRepositoryInterface $artistRepository,
                                ArtistService            $artistService)
    {
        $this->albumRepository = $albumRepository;
        $this->albumService = $albumService;
        $this->albumLogger = $albumLogger;
        $this->artistRepository = $artistRepository;
        $this->artistService = $artistService;
    }

    public function index(): View
    {
        $albums = $this->albumRepository->getAllPaginated(5);
        $artistsForFilter = $this->artistRepository->getAllByUniqueNames();
        return view('albums', compact('albums', 'artistsForFilter'));
    }

    public function indexFiltered(FilterRequest $request): View
    {
        $albums = $this->albumRepository->getFilteredByArtist($request->artist, 5);
        $artistsForFilter = $this->artistRepository->getAllByUniqueNames();
        return view('albums', compact('albums', 'artistsForFilter'));
    }

    public function create(): View
    {
        return view('create-album');
    }

    public function edit(int $albumId): RedirectResponse|View
    {
        $album = $this->albumRepository->getById($albumId);
        $artist = $album->artist;
        if (isset($album)) {
            return view('create-album', compact('album', 'artist'));
        }
        return redirect()->back();
    }

    public function store(AlbumRequest $request): RedirectResponse
    {
        $artist = $this->artistRepository->getByName($request->artist);

        if (!isset($artist)) {
            $artist = $this->artistService->make(['name' => $request->artist, 'img' => null]);
        }
        $this->albumService->make($request->validated(), $artist->id);
        $this->albumLogger->logAddedAlbum($request->validated());
        return redirect(RouteServiceProvider::HOME);
    }

    public function update(AlbumRequest $request): RedirectResponse
    {
        $album = $this->albumRepository->getById($request->id);
        $artist = $this->artistRepository->getByName($request->artist);
        if (!isset($artist)) {
            $artist = $this->artistService->make(['name' => $request->artist, 'img' => null]);
        }

        if (!isset($album)) {
            $this->albumService->make($request->validated(), $artist->id);
            $this->albumLogger->logAddedAlbum($request->validated());
            return redirect(RouteServiceProvider::HOME);
        }

        $oldAlbum = clone $album;
        $this->albumService->edit($album, $request->validated(), $artist->id);
        $this->albumLogger->logEditedAlbum($oldAlbum, $request->validated());

        return redirect(RouteServiceProvider::HOME);
    }

    public function destroy(DeleteAlbumRequest $request): RedirectResponse
    {
        $album = $this->albumRepository->getById($request->id);
        if (!isset($album)) {
            return redirect(RouteServiceProvider::HOME);
        }

        $this->albumLogger->logDeletedAlbum($album);
        $this->albumService->delete($album);

        return redirect()->back();
    }
}
