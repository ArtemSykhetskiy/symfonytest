<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\Handler\ProductFormHandler;
use App\Form\ProductFormType;
use App\Form\ProfileEditFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route ("/admin")
 */
class ProductController extends AbstractController
{
    #[Route('/products', methods: 'get', name: 'admin.products')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', ['products' => $products]);
    }

    #[Route('/products/add', methods: 'get', name: 'admin.add.products')]
    public function create(): Response
    {
        $form = $this->createForm(ProductFormType::class);

        return $this->render('product/add.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/products/store', methods: 'post', name: 'admin.store.products')]
    public function store(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setTitle($_POST['product_form']['Title']);
        $product->setDescription($_POST['product_form']['Description']);
        $product->setSKU($_POST['product_form']['SKU']);
        $product->setPrice($_POST['product_form']['Price']);

        $entityManager->persist($product);

        $entityManager->flush();

        return $this->redirectToRoute('admin.products');
    }

    #[Route('/products/edit/{id}', name: 'admin.edit.products')]
    public function edit(Request $request, ProductFormHandler $productFormHandler , Product $product): Response
    {
        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
          $product = $productFormHandler->processEditForm($product, $form);

            return $this->redirectToRoute('admin.products');
        }

        return $this->render('product/edit.html.twig', ['form' => $form->createView(), 'product' => $product]);
    }

    #[Route('/products/remove/{id}', name: 'admin.remove.products')]
    public function remove(Product $product, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('admin.products');
    }

}
