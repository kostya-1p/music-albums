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
        'artist',
        'description',
        'img'
    ];

    public static function storeAlbum(Album $album, string $name, string $artist, string $description, string $img) {
        $album->name = $name;
        $album->artist = $artist;
        $album->description = $description;
        $album->img = $img;

        $album->save();
    }
}
