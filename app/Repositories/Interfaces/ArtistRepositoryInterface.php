<?php

namespace App\Repositories\Interfaces;

use App\Models\Artist;
use Illuminate\Pagination\LengthAwarePaginator;

interface ArtistRepositoryInterface
{
    public function getAllPaginated(int $pageSize): LengthAwarePaginator;

    public function getFiltered(string $artistName, int $pageSize): LengthAwarePaginator;

    public function getById(int $artistId): ?Artist;

    public function getByName(string $artistName): ?Artist;
}
