<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    private  $entityManager;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager =  $doctrine;
    }

    #[Route('/products', name: 'products')]
    public function index(): Response
    {
        $entityManager = $this->entityManager->getManager();
        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('productPage/index.html.twig', ['products' => $products]);
    }

    #[Route('product/{id}', name: 'product.show')]
    public function show(Product $product)
    {
        return $this->render('productPage/show.html.twig', ['product' => $product]);
    }


}
