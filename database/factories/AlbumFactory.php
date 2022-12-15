<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Album>
 */
class AlbumFactory extends Factory
{
    private function getRandomArtistId(): int
    {
        $randomArtist = $this->faker->randomElement(Artist::all());
        return $randomArtist->id;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $storageImagePaths = Storage::disk('images')->files('albums');
        $randFileNameIndex = rand(0, count($storageImagePaths) - 1);

        return [
            'name' => $this->faker->word(),
            'artist_id' => $this->getRandomArtistId(),
            'description' => $this->faker->realText(),
            'img' => empty($storageImagePaths) ? 'alternative.png' : basename($storageImagePaths[$randFileNameIndex]),
        ];
    }
}
