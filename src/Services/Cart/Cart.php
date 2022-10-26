<?php

namespace App\Services\Cart;

use App\Entity\CartProduct;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class Cart
{

    private $entityManager;
    private $cartRepository;
    private $cartProductRepository;
    private $productRepository;
    public function __construct(ManagerRegistry $doctrine, CartProductRepository $cartProductRepository,CartRepository $cartRepository,  ProductRepository $productRepository)
    {
        $this->entityManager =  $doctrine;
        $this->cartProductRepository = $cartProductRepository;
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    public function addToCart(Request $request)
    {
        $sessionId = $request->cookies->get('PHPSESSID');
        $productId = $request->query->get('id');

        $product = $this->productRepository->find($productId);

        $cart = $this->cartRepository->findOneBy(['session_id' => $sessionId]);

        if(!$cart){
            $cart = new \App\Entity\Cart();
            $cart->setSessionId($sessionId);
        }

        $cartProduct = $this->cartProductRepository->findOneBy(['cart' => $cart, 'product' => $product ]);

        if(!$cartProduct){
            $cartProduct = new CartProduct();
            $cartProduct->setCart($cart);
            $cartProduct->setQuantity(1);
            $cartProduct->setProduct($product);
        }else{
            $quantity = $cartProduct->getQuantity() + 1;
            $cartProduct->setQuantity($quantity);
        }

        $cart->addCartProduct($cartProduct);

        $entityManager = $this->entityManager->getManager();
        $entityManager->persist($cart);
        $entityManager->persist($cartProduct);
        $entityManager->flush();

        return $entityManager;
    }

}