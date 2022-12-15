<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'name' => $this->faker->word(),
            'artist_id' => $this->getRandomArtistId(),
            'description' => $this->faker->realText(),
            'img' => FactoryHelper::copyRandomImageInStorage('albums'),
        ];
    }
}
