<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'img'
    ];

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class, 'artist_id', 'id');
    }
}
