<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'artist_id',
        'description',
        'img'
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id', 'id');
    }
}
