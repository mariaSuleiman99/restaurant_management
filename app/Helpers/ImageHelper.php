<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Upload an image to the specified directory.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string|null
     */
    public static function uploadImage(UploadedFile $file, string $directory = 'images'): ?string
    {
        if ($file) {
            // Store the file in the specified directory (e.g., 'public/images')
            return 'storage/'.$file->store($directory, 'public');
        }

        return null;
    }

    /**
     * Delete an image from storage.
     *
     * @param string|null $filePath
     * @return void
     */
    public static function deleteImage(?string $filePath): void
    {
        if ($filePath) {
            Storage::disk('public')->delete($filePath);
        }
    }
}
