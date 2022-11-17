<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ArtistRepositoryInterface;
use Illuminate\Contracts\View\View;

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
}
