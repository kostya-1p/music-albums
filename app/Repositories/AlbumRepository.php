<?php

namespace App\Repositories;

use App\Models\Album;
use App\Repositories\Interfaces\AlbumRepositoryInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AlbumRepository implements AlbumRepositoryInterface
{
    public function getById(int $albumId): ?Album
    {
        return Album::find($albumId);
    }

    public function getAllPaginated(int $pageSize): LengthAwarePaginator
    {
        return $this->getAlbumArtistsJoinQuery()
            ->paginate($pageSize);
    }

    public function getFilteredByArtist(string $artistName, int $pageSize): LengthAwarePaginator
    {
        return $this->getAlbumArtistsJoinQuery()
            ->where('artists.name', 'LIKE', "%{$artistName}%")
            ->paginate($pageSize);
    }

    private function getAlbumArtistsJoinQuery(): Builder
    {
        return DB::table('albums')
            ->join('artists', 'artists.id', '=', 'albums.artist_id')
            ->select('albums.*', 'artists.name as artist');
    }
}
