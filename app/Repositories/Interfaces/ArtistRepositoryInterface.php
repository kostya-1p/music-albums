<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface ArtistRepositoryInterface
{
    public function getAllPaginated(int $pageSize): LengthAwarePaginator;

    public function getFiltered(string $artistName, int $pageSize): LengthAwarePaginator;
}
