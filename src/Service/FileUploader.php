<?php

declare(strict_type=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * @var string
     */
    private $uploadsPath;

    /**
     * FileUploader constructor.
     *
     * @param string $uploadsPath
     */
    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    }

    /**
     * @param UploadedFile $uploadedFile
     *
     * @return string
     */
    public function uploadImage(UploadedFile $uploadedFile): string
    {
        $destination = $this->uploadsPath;

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        $uploadedFile->move($destination, $newFilename);

        return $newFilename;
    }
}
