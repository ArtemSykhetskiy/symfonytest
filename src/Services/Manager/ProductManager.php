<?php

namespace App\Services\Manager;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProductManager
{
    private $entityManager;
    private $productImagesDir;
    private $productImageManager;

    public function __construct(EntityManagerInterface $entityManager, ProductImageManager $productImageManager , string $productImagesDir)
    {
        $this->entityManager = $entityManager;
        $this->productImagesDir = $productImagesDir;
        $this->productImageManager = $productImageManager;
    }

    public function getRepository() : ObjectRepository
    {
        return $this->entityManager->getRepository(Product::class);
    }

    public function save(Product $product)
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function remove()
    {

    }

    public function getProductImagesDir(Product $product)
    {
        return sprintf('%s/%s', $this->productImagesDir, $product->getId());
    }

    public function updateProductImages(Product $product, string $tempImageFilename = null) : Product
    {
        if(!$tempImageFilename){
            return $product;
        }

        $productDir = $this->getProductImagesDir($product);

        $productImage = $this->productImageManager->saveImageForProduct($productDir, $tempImageFilename);
        $productImage->setProduct($product);

        $product->addProductImage($productImage);
        return $product;
    }



}
