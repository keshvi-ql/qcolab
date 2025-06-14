<?php
namespace App\Service;

use Laminas\Diactoros\UploadedFile;
use Cake\Http\Exception\InternalErrorException;

class FileService
{
    // Define the upload path
    protected string $uploadPath = WWW_ROOT . 'uploads' . DS;

    public function __construct()
    {
        // Ensure the upload directory exists
        if (!file_exists($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

    /**
     * Handle file upload
     * @param array $fileData Array containing file info from the form submission
     * @param string|null $customName Optionally provide a custom file name
     * @return string The file name saved on the server
     * @throws InternalErrorException If the upload fails
     */
    public function upload(UploadedFile $fileData, ?string $customName = null): string
    {
        // Check for upload errors
        if ($fileData->getError() !== UPLOAD_ERR_OK) {
            throw new InternalErrorException('File upload failed.');
        }

        // Generate a unique file name if no custom name is provided
        $fileName = $customName ?? uniqid() . '-' . $fileData->getClientFilename();

        // Define the target path
        $targetPath = $this->uploadPath . $fileName;

        // Move the uploaded file
        $fileData->moveTo($targetPath);

        return $fileName;
    }

    /**
     * Delete a file from the server
     * @param string $fileName The name of the file to delete
     * @return bool True if the file was deleted, false otherwise
     */
    public function delete(string $fileName): bool
    {
        // Get the full file path
        $filePath = $this->uploadPath . $fileName;

        // Check if the file exists
        if (!file_exists($filePath)) {
            throw new InternalErrorException('File not found.');
        }

        // Attempt to delete the file
        if (!unlink($filePath)) {
            throw new InternalErrorException('Failed to delete file.');
        }

        return true;
    }

    /**
     * Get the file path for a given file name
     * @param string $fileName The name of the file
     * @return string The full file path
     */
    public function getFilePath(string $fileName): string
    {
        return $this->uploadPath . $fileName;
    }
}
