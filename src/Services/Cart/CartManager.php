<?php

namespace App\Services\Cart;

use App\Entity\Cart;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

class CartManager
{
    private $entityManager;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine;
    }

    public function getRepository(): ObjectRepository
    {
        $entityManager = $this->entityManager->getManager();
        return $entityManager->getRepository(Cart::class);
    }
}