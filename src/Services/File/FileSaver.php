<?php

namespace App\Services\File;

use App\Services\Filesystem\FilesystemWorker;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileSaver
{
    private $slugger;
    private $uploadsTempDir;
    private $filesystemWorker;

    public function __construct(SluggerInterface $slugger, string $uploadsTempDir, FilesystemWorker $filesystemWorker)
    {
        $this->slugger = $slugger;
        $this->uploadsTempDir = $uploadsTempDir;
        $this->filesystemWorker = $filesystemWorker;
    }

    public function saveUploadedFileIntoTemp(UploadedFile $uploadedFile)
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);

        $filename = sprintf('%s-%s.%s', $safeFilename, uniqid(), $uploadedFile->guessExtension());

        $this->filesystemWorker->createFolderIfItNotExist($this->uploadsTempDir);


        try {
            $uploadedFile->move($this->uploadsTempDir, $filename);
        }catch (\Exception $exception){
            return null;
        }

        return $filename;
    }
}