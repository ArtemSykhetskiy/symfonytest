<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/admin/products', methods: 'get', name: 'admin.products')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', ['products' => $products]);
    }

    #[Route('/admin/products/add', methods: 'get', name: 'admin.add.products')]
    public function create(): Response
    {
        $form = $this->createForm(ProductFormType::class);

        return $this->render('product/add.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/admin/products/store', methods: 'post', name: 'admin.store.products')]
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

    #[Route('/admin/products/edit/{id}', methods: 'get', name: 'admin.edit.products')]
    public function edit(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductFormType::class);
        $form->getData($product);

        return $this->render('product/edit.html.twig', ['form' => $form->createView(), 'product' => $product]);
    }

    #[Route('/admin/products/update/{id}', methods: 'post', name: 'admin.update.products')]
    public function update(Request $request, int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        $product->setTitle($_POST['product_form']['Title']);
        $product->setDescription($_POST['product_form']['Description']);
        $product->setSKU($_POST['product_form']['SKU']);
        $product->setPrice($_POST['product_form']['Price']);

        $entityManager->merge($product);

        $entityManager->flush();

        return $this->redirectToRoute('admin.products');
    }

}
