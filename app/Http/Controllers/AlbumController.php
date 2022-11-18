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
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

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
        return view('albums', compact('albums'));
    }

    public function indexFiltered(FilterRequest $request): View
    {
        $albums = $this->albumRepository->getFilteredByArtist($request->artist, 5);
        return view('albums', compact('albums'));
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

    public function searchAlbumByName(string $albumName): string
    {
        $apiKey = env('API_KEY');
        $response = Http::get("http://ws.audioscrobbler.com/2.0/?method=album.search&album={$albumName}&api_key={$apiKey}&format=json");
        $responseArray = $response->json();

        $artistsAndImages = [];
        $artistsAndImages['artists'] = $this->getArrayOfArtists($responseArray);
        $artistsAndImages['images'] = $this->getArrayOfImages($responseArray);

        return json_encode($artistsAndImages);
    }

    private function getArrayOfArtists(array $responseArray)
    {
        $artistsArray = $this->getArrayFromResponse($responseArray, 'artist');
        $uniqueArtists = array_unique($artistsArray);
        return array_values($uniqueArtists);
    }

    private function getArrayOfImages(array $responseArray)
    {
        $imagesFullArray = $this->getArrayFromResponse($responseArray, 'image');
        $largeImages = [];
        $indexLargeImages = 2;

        foreach ($imagesFullArray as $image) {
            $largeImages[] = $image[$indexLargeImages]['#text'];
        }

        return $largeImages;
    }

    private function getArrayFromResponse(array $responseArray, string $key)
    {
        $array = [];
        $albums = $responseArray['results']['albummatches']['album'];

        foreach ($albums as $album) {
            $array[] = $album[$key];
        }

        return $array;
    }

    public function getAlbumDescription(string $albumName, string $artistName): string
    {
        $apiKey = env('API_KEY');
        $response = Http::get("http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key={$apiKey}&artist={$artistName}&album={$albumName}&format=json");
        $responseArray = $response->json();
        $description = '';

        if (array_key_exists('album', $responseArray) && array_key_exists('wiki', $responseArray['album'])) {
            $description = $responseArray['album']['wiki']['summary'];
        }

        return json_encode(['description' => $description]);
    }
}
