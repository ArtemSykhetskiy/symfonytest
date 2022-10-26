<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Services\Order\OrderManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CartController extends AbstractController
{
    private $entityManager;
    private $cart;
    public function __construct(ManagerRegistry $doctrine, \App\Services\Cart\Cart $cart)
    {
        $this->entityManager =  $doctrine;
        $this->cart = $cart;
    }

    #[Route('/cart', methods: 'get',  name: 'cart')]
    public function showCart(Request $request, CartRepository $cartRepository)
    {
        $sessionId = $request->cookies->get('PHPSESSID');
        $cart = $cartRepository->findOneBy(['session_id' => $sessionId]);

        return $this->render('cart/index.html.twig', ['cart' => $cart]);
    }

    #[Route('/cart/save',  name: 'cart.save')]
    public function addToCart(Request $request): Response
    {
       $addToCart = $this->cart->addToCart($request);

       return $this->redirectToRoute('cart');
    }

    #[Route('cart/create', name: 'cart.create')]
    public function create(Request $request, OrderManager $orderManager)
    {
        $sessionId = $request->cookies->get('PHPSESSID');
        $orderManager->createOrderFromCartBySessionId($sessionId);


    }
}
