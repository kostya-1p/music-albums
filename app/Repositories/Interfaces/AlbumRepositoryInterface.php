<?php

namespace App\Repositories\Interfaces;

use App\Models\Album;
use Illuminate\Database\Eloquent\Collection;

interface AlbumRepositoryInterface
{
    public function getById(int $albumId): ?Album;

    public function getAllPaginated(int $pageSize);
}
