<?php

<<<<<<< HEAD
namespace App;

=======

namespace App;


>>>>>>> daf90324689f116017e1e50a3d230c376734f133
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
<<<<<<< HEAD
=======

>>>>>>> daf90324689f116017e1e50a3d230c376734f133
    private $uploadsPath;

    /**
     * FileUploader constructor.
<<<<<<< HEAD
=======
     * @param string $uploadsPath
>>>>>>> daf90324689f116017e1e50a3d230c376734f133
     */
    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    }

    public function uploadImage(UploadedFile $uploadedFile): string
    {
        $destination = $this->uploadsPath;

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile->move($destination, $newFilename);

        return $newFilename;
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> daf90324689f116017e1e50a3d230c376734f133
