<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Http\Requests\DeleteAlbumRequest;
use App\Logging\AlbumLogger;
use App\Models\Album;
use App\Providers\RouteServiceProvider;
use App\Repositories\Interfaces\AlbumRepositoryInterface;
use App\Services\AlbumService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AlbumController extends Controller
{
    private AlbumRepositoryInterface $albumRepository;
    private AlbumService $albumService;
    private AlbumLogger $albumLogger;

    public function __construct(AlbumRepositoryInterface $albumRepository, AlbumService $albumService,
                                AlbumLogger              $albumLogger)
    {
        $this->albumRepository = $albumRepository;
        $this->albumService = $albumService;
        $this->albumLogger = $albumLogger;
    }

    public function index(): View
    {
        $albums = $this->albumRepository->getAllPaginated(5);
        return view('albums', compact('albums'));
    }

    public function create(): View
    {
        return view('create-album');
    }

    public function edit(int $albumId): RedirectResponse|View
    {
        $album = $this->albumRepository->getById($albumId);
        if (isset($album)) {
            return view('create-album', compact('album'));
        }
        return redirect()->back();
    }

    public function store(AlbumRequest $request): RedirectResponse
    {
        $this->albumService->make($request->validated());
        $this->albumLogger->logAddedAlbum($request->validated());
        return redirect(RouteServiceProvider::HOME);
    }

    public function update(AlbumRequest $request): RedirectResponse
    {
        $album = $this->albumRepository->getById($request->id);
        if (!isset($album)) {
            $this->albumService->make($request->validated());
            $this->albumLogger->logAddedAlbum($request->validated());
            return redirect(RouteServiceProvider::HOME);
        }

        $oldAlbum = clone $album;
        $this->albumService->edit($album, $request->validated());
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

    private function storeAlbum(Request $request, Album $album)
    {
        $this->validateAlbumData($request);
        $oldAlbumData = clone $album;
        $isEditing = isset($album->id);

        if ($this->isValidImageURL($request->img)) {
            Album::storeAlbum($album, $request->name, $request->artist, $request->description, $request->img);
            $this->logAlbumChanges($isEditing, $oldAlbumData, $album);
            return redirect(RouteServiceProvider::HOME);
        }

        return redirect(RouteServiceProvider::HOME);
    }

    private function isValidImageURL(string $img)
    {
        if (filter_var($img, FILTER_VALIDATE_URL)) {
            $headers = get_headers($img, 1);
            if (strpos($headers['Content-Type'], 'image/') !== false) {
                return true;
            }
        }

        return false;
    }

    private function validateAlbumData(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'artist' => ['required', 'string'],
            'img' => ['required', 'string']
        ]);
    }

    private function logAlbumChanges(bool $isEditing, Album $oldAlbumData, Album $album)
    {
        if ($isEditing) {
            $this->logEditedAlbum($oldAlbumData, $album);
            return;
        }

        $this->logAddedAlbum($album);
    }

    private function logAddedAlbum(Album $album)
    {
        Log::channel('singleAlbums')->info('Album added', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'album_id' => $album->id,
            'album_name' => $album->name,
            'album_artist' => $album->artist]);
    }

    private function logEditedAlbum(Album $oldAlbumData, Album $newAlbumData)
    {
        Log::channel('singleAlbums')->info('Album edited', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'album_id' => $oldAlbumData->id,
            'old_album_name' => $oldAlbumData->name,
            'old_album_artist' => $oldAlbumData->artist,
            'old_album_img' => $oldAlbumData->img,
            'new_album_name' => $newAlbumData->name,
            'new_album_artist' => $newAlbumData->artist,
            'new_album_img' => $newAlbumData->img,]);
    }

    private function logDeletedAlbum(Album $album)
    {
        Log::channel('singleAlbums')->info('Album deleted', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'album_id' => $album->id,
            'album_name' => $album->name,
            'album_artist' => $album->artist]);
    }

    //TODO write API for functions below
    public function searchAlbumByName(string $albumName)
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

    public function getAlbumDescription(string $albumName, string $artistName)
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
