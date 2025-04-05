<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Upload an image file to the specified directory.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string
     */
    public function upload(UploadedFile $file, string $directory = 'uploads'): string
    {
        // Generate a unique filename
        $filename = $this->generateFilename($file);

        // Store the file in the public disk
        $path = $file->storeAs($directory, $filename, 'public');

        return Storage::url($path);
    }

    /**
     * Generate a unique filename for the uploaded file.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $timestamp = time();
        $random = Str::random(8);

        return Str::slug($originalName) . '-' . $timestamp . '-' . $random . '.' . $extension;
    }

    /**
     * Delete an image from storage.
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        // Check if the file exists in storage
        if (Storage::exists($path)) {
            return Storage::delete($path);
        }

        return false;
    }
}