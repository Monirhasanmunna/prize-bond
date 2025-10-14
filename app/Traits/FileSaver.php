<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait FileSaver
{
    /**
     * Upload a file, remove old file if needed, and return the new file path.
     *
     * @param object $file
     * @param string $basePath
     * @param string|null $stringName
     * @param string|null $oldPath
     * @return string
     */
    public function upload_file(object $file, string $basePath, string $stringName = null, string $oldPath = null): string
    {
        // Delete the old file if exists
        if ($oldPath) {
            $fullOldPath = public_path($oldPath);

            // Check if the old file exists before deleting
            if (file_exists($fullOldPath)) {
                unlink($fullOldPath);
                Log::info('Old file deleted: ' . $fullOldPath);
            } else {
                Log::warning('Old file not found for deletion: ' . $fullOldPath);
            }
        }

        // Validate the uploaded file before processing
        if (!$file->isValid()) {
            throw new \Exception('File upload failed or file is invalid.');
        }

        // Generate a new unique file name
        $newFileName = str()->slug($stringName) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Define the directory path
        $directory = public_path('uploads/' . $basePath);

        // Create the directory if it doesn't exist
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Move the file to the designated directory
        $file->move($directory, $newFileName);

        // Return the relative file path for future access (you can return the URL here if necessary)
        return 'uploads/' . $basePath . '/' . $newFileName;
    }
}
