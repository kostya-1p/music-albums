<?php

namespace App\Repositories;

use App\Models\Artist;
use App\Repositories\Interfaces\ArtistRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ArtistRepository implements ArtistRepositoryInterface
{
    public function getAllPaginated(int $pageSize): LengthAwarePaginator
    {
        return Artist::paginate($pageSize);
    }
}
