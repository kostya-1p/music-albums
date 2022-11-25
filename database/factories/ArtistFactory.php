<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artist>
 */
class ArtistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $storageImagePaths = Storage::disk('images')->files('artists');
        $randFileNameIndex = rand(0, count($storageImagePaths) - 1);

        return [
            'name' => $this->faker->name(),
            'img' => empty($storageImagePaths) ? 'alternative.png' : basename($storageImagePaths[$randFileNameIndex]),
        ];
    }
}
