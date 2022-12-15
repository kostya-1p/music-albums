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

    private function getRandomImageNameFromStorage(): ?string
    {
        $storageImages = Storage::disk('images')->files('albums');
        if (empty($storageImages)) {
            return null;
        }
        $randFileNameIndex = rand(0, count($storageImages) - 1);
        return $storageImages[$randFileNameIndex];
    }

    private function copyRandomImageInStorage(): string
    {
        $oldFileName = $this->getRandomImageNameFromStorage();
        if (!isset($oldFileName)) {
            return 'alternative.png';
        }
        $extension = substr($oldFileName, strrpos($oldFileName, '.') + 1);
        $newFileName = uniqid(more_entropy: true) . '.' . $extension;
        Storage::disk('images')->copy($oldFileName, "albums/$newFileName");
        return $newFileName;
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
            'img' => $this->copyRandomImageInStorage(),
        ];
    }
}
