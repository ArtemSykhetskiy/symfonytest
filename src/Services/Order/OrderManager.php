<?php

namespace App\Services\Order;

use App\Entity\Cart;
use App\Entity\Order;
use App\Repository\CartRepository;
use App\Services\Cart\CartManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class OrderManager
{
    private $cartManager;
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager, CartManager $cartManager)
    {
        $this->entityManager = $entityManager;
        $this->cartManager = $cartManager;

    }

    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Order::class);
    }

    public function createOrderFromCartBySessionId(string $sessionId)
    {
        $cart = $this->cartManager->getRepository()->findOneBy(['session_id' => $sessionId]);

        if($cart){
            $this->createOrderFromCart($cart);
        }
    }

    public function createOrderFromCart(Cart $cart)
    {
        dd($cart);
    }


}