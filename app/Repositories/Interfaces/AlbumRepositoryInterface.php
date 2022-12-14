<?php

namespace App\Repositories\Interfaces;

use App\Models\Album;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AlbumRepositoryInterface
{
    public function getById(int $albumId): ?Album;

    public function getAllPaginated(int $pageSize): LengthAwarePaginator;

    public function getFilteredByArtist(string $artistName, int $pageSize): LengthAwarePaginator;
}
