<?php

namespace App\Repositories;

use App\Models\Album;
use App\Repositories\Interfaces\AlbumRepositoryInterface;

class AlbumRepository implements AlbumRepositoryInterface
{
    public function getById(int $albumId): ?Album
    {
        return Album::find($albumId);
    }

    public function getAllPaginated(int $pageSize)
    {
        return Album::paginate(5);
    }
}
