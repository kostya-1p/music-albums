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

    public function getFiltered(string $artistName, int $pageSize): LengthAwarePaginator
    {
        return Artist::where('name', 'like', "%{$artistName}%")->paginate($pageSize);
    }
}