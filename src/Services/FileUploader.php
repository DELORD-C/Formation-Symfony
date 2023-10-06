<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader {
    function __construct(
        private readonly SluggerInterface $slugger,
        private readonly string $uploadDirectory
    ) {}

    function uploadFile (UploadedFile $file, string $path = ''): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move(
              '/public' . $this->uploadDirectory . $path,
              $newFilename
            );
        } catch (FileException $e) {
            throw new FileException($e);
        }

        return $newFilename;
    }
}