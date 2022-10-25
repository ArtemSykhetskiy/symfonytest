<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Services\File\FileSaver;
use App\Services\Manager\ProductManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;

class ProductFormHandler
{
    private $fileSaver;
    private $productManager;

    public function __construct(ProductManager $productManager, FileSaver $fileSaver){
        $this->fileSaver = $fileSaver;
        $this->productManager = $productManager;
    }

    public function processEditForm(Product $product, Form $form)
    {
        $this->productManager->save($product);

        $newImageFile = $form->get('newImage')->getData();

        $tempImageFileName = $newImageFile
            ? $this->fileSaver->saveUploadedFileIntoTemp($newImageFile)
            : null;

        $this->productManager->updateProductImages($product, $tempImageFileName);


        $this->productManager->save($product);

        return $product;
    }

}
