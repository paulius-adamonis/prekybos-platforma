<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $imageDirectory;

    public function __construct($imageDirectory)
    {
        $this->imageDirectory = $imageDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        try {
            $file->move($this->getImageDirectory(), $fileName);
        } catch (FileException $e) {
            return null;
        }

        return $fileName;
    }

    public function getImageDirectory()
    {
        return $this->imageDirectory;
    }
}