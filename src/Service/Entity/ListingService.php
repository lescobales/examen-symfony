<?php

namespace App\Service\Entity;

use App\Entity\Listing;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ListingService
{

    public function __construct(
        private FileUploader $fileUploader
    ) { }

    /**
     * @param UploadedFile|null $uploadedFile
     * @param Listing $listing
     */
    public function handleFileUpload(?UploadedFile $uploadedFile, Listing $listing): void {
        if ($uploadedFile !== null) {
            $listing->setImage(
                $this->fileUploader->uploadFile(
                    $uploadedFile,
                    '/listing'
                )
            );
        }
    }

}
