<?php

declare(strict_types=1);

namespace App\Utility;

use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;

class FileHelper
{
    /**
     * Upload a file to a specified directory with a dynamic name.
     *
     * @param \Cake\Datasource\EntityInterface $file The file to be uploaded.
     * @param string $uploadPath The directory where the file should be uploaded.
     * @param string|null $prefix Optional prefix for the file name.
     * @return string The uploaded file name.
     * @throws BadRequestException If the file upload fails.
     */
    public static function uploadFile($file, $uploadPath, $prefix = null)
    {
        if ($file && !$file->getError()) {
            // Generate a unique file name with optional prefix
            $filename = ($prefix ? $prefix . '-' : '') . uniqid() . '-' . $file->getClientFilename();

            // Ensure the upload path exists
            $uploadDir = WWW_ROOT . $uploadPath;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            // Move the file to the upload path
            $filePath = $uploadDir . DS . $filename;
            $file->moveTo($filePath);

            return $filename;
        } else {
            throw new BadRequestException('Invalid file upload.');
        }
    }

    public static function moveFilesFromTempDirToPermanentDir($tempDir, $finalDir, $uploadedFiles)
    {
        // Ensure directories exist; create if they do not
        if (!is_dir(WWW_ROOT . $tempDir)) {
            mkdir(WWW_ROOT . $tempDir, 0755, true);
        }
        if (!is_dir(WWW_ROOT . $finalDir)) {
            mkdir(WWW_ROOT . $finalDir, 0755, true);
        }

        // Handle each file in the list of uploaded files
        $filenames = explode(',', $uploadedFiles);

        foreach ($filenames as $filename) {
            if ($filename) {
                $tempFilePath = WWW_ROOT . $tempDir . DS . $filename;
                $finalFilePath = WWW_ROOT . $finalDir . DS . $filename;

                // Move file if it exists in the temporary directory
                if (file_exists($tempFilePath)) {
                    rename($tempFilePath, $finalFilePath);
                }
            }
        }

        // Clean up temporary files
        self::deleteAllTempFiles($tempDir);

        return true;
    }

    public static function deleteFile($fileId, $uploadPath)
    {
        $filePath = WWW_ROOT . $uploadPath . DS . $fileId;

        if (file_exists($filePath)) {
            return unlink($filePath);
        }

        return false; // Return false to indicate the file was not deleted
    }

    public static function deleteAllTempFiles($uploadPath)
    {
        $tempDir = WWW_ROOT . $uploadPath;

        if (!is_dir($tempDir)) {
            throw new NotFoundException('Directory not found.');
        }

        $files = glob($tempDir . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        return true;
    }
}
