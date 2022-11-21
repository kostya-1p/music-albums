<?php

namespace App\Repositories;

use App\Models\Artist;
use App\Repositories\Interfaces\ArtistRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ArtistRepository implements ArtistRepositoryInterface
{
    public function getAllPaginated(int $pageSize): LengthAwarePaginator
    {
        return Artist::paginate($pageSize);
    }

    public function getFiltered(string $artistName, int $pageSize): LengthAwarePaginator
    {
        return Artist::where('name', $artistName)->paginate($pageSize);
    }

    public function getById(int $artistId): ?Artist
    {
        return Artist::find($artistId);
    }

    public function getByName(string $artistName): ?Artist
    {
        return Artist::where('name', $artistName)->first();
    }

    public function getByAlbums(LengthAwarePaginator $albums): Collection
    {
        $artists = collect();
        foreach ($albums as $album) {
            $artists->push($album->artist);
        }
        return $artists;
    }
}
