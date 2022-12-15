<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Storage;

class FactoryHelper
{
    static public function getRandomImageNameFromStorage(string $dir): ?string
    {
        $storageImages = Storage::disk('images')->files($dir);
        if (empty($storageImages)) {
            return null;
        }
        $randFileNameIndex = rand(0, count($storageImages) - 1);
        return $storageImages[$randFileNameIndex];
    }

    static public function copyRandomImageInStorage(string $dir): string
    {
        $oldFileName = self::getRandomImageNameFromStorage($dir);
        if (!isset($oldFileName)) {
            return 'alternative.png';
        }
        $extension = substr($oldFileName, strrpos($oldFileName, '.') + 1);
        $newFileName = uniqid(more_entropy: true) . '.' . $extension;
        Storage::disk('images')->copy($oldFileName, "$dir/$newFileName");
        return $newFileName;
    }
}
