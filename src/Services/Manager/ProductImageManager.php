<?php

namespace App\Services\Manager;

use App\Entity\ProductImage;
use App\Services\File\ImageResizer;
use App\Services\Filesystem\FilesystemWorker;
use Doctrine\ORM\EntityManagerInterface;

class ProductImageManager
{
    private $entityManager;
    private $filesystemWorker;
    private $uploadsTempDir;
    private $imageResizer;

    public function __construct(EntityManagerInterface $entityManager, FilesystemWorker $filesystemWorker, ImageResizer $imageResizer, string $uploadsTempDir)
    {
        $this->entityManager = $entityManager;
        $this->filesystemWorker = $filesystemWorker;
        $this->uploadsTempDir = $uploadsTempDir;
        $this->imageResizer = $imageResizer;
    }

    public function saveImageForProduct(string $productDir, string $tempImageFilename = null)
    {
        if(!$tempImageFilename){
            return null;
        }

        $this->filesystemWorker->createFolderIfItNotExist($productDir);

        $filenameId = uniqid();

//        $imageSmallParams = [
//            'width' => '60',
//            'height' => null,
//            'newFolder' => $productDir,
//            'newFilename' => sprintf('%s_%s.png', $filenameId, 'small')
//        ];
//        $imageSmall = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename, $imageSmallParams);
//
//        $imageMiddleParams = [
//            'width' => '430',
//            'height' => null,
//            'newFolder' => $productDir,
//            'newFilename' => sprintf('%s_%s.png', $filenameId, 'middle')
//        ];
//        $imageMiddle = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename, $imageMiddleParams);;
//
//        $imageBigParams = [
//            'width' => '800',
//            'height' => null,
//            'newFolder' => $productDir,
//            'newFilename' => sprintf('%s_%s.png', $filenameId, 'big')
//        ];
//        $imageBig = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename, $imageBigParams);;

        $productImage = new ProductImage();
        $productImage->setFilenameSmall($tempImageFilename);
        $productImage->setFilenameMiddle($tempImageFilename);
        $productImage->setFilenameBig($tempImageFilename);

        return $productImage;
    }
}