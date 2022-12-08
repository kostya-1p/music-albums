<?php

namespace App\Repositories;

use App\Models\Album;
use App\Models\Artist;
use App\Repositories\Interfaces\AlbumRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class AlbumRepository implements AlbumRepositoryInterface
{
    public function getById(int $albumId): ?Album
    {
        return Album::find($albumId);
    }

    public function getAllPaginated(int $pageSize): LengthAwarePaginator
    {
        return Album::paginate($pageSize);
    }

    public function getFilteredByArtist(string $artistName, int $pageSize): LengthAwarePaginator
    {
        $artists = Artist::where('name', $artistName)->paginate($pageSize);
        $artistsIds = $artists->pluck('id');
        return Album::whereIn('artist_id', $artistsIds)->paginate($pageSize);
    }
}
