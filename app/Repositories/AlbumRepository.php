<?php

namespace App\Repositories;

use App\Models\Album;
use App\Models\Artist;
use App\Repositories\Interfaces\AlbumRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AlbumRepository implements AlbumRepositoryInterface
{
    public function getById(int $albumId): ?Album
    {
        return Album::find($albumId);
    }

    public function getAllPaginated(int $pageSize)
    {
        return DB::table('albums')
            ->join('artists', 'artists.id', '=', 'albums.artist_id')
            ->select('albums.*', 'artists.name as artist')
            ->paginate($pageSize);
    }
}
