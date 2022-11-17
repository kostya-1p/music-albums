<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Repositories\Interfaces\ArtistRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    private ArtistRepositoryInterface $artistRepository;

    public function __construct(ArtistRepositoryInterface $artistRepository)
    {
        $this->artistRepository = $artistRepository;
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
}
