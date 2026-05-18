<?php

namespace App\Support\Files;

/**
 * Handles safe cleanup of files stored under the public directory.
 *
 * This helper prevents repeated raw file_exists/unlink logic across modules.
 */
class FileCleanup
{
    /**
     * Delete a file from the public directory using a relative path.
     *
     * Example:
     * uploads/student-documents/1/file.pdf
     *
     * @param string|null $relativePath
     * @return void
     */
    public static function deletePublicFile(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        $path = public_path($relativePath);

        if (file_exists($path) && is_file($path)) {
            unlink($path);
        }
    }
}