<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface ArtistRepositoryInterface
{
    public function getAllPaginated(int $pageSize): LengthAwarePaginator;
}
