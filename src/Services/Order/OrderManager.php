<?php

namespace App\Services\Order;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
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

    public function createOrderFromCartBySessionId(string $sessionId, User $user)
    {
        $cart = $this->cartManager->getRepository()->findOneBy(['session_id' => $sessionId]);

        if($cart){
            $this->createOrderFromCart($cart, $user);
        }
    }

    public function addOrderProductsFromCart(Order $order, int $cartId)
    {
        /** @var Cart $cart */
        $cart = $this->cartManager->getRepository()->find($cartId);

        if ($cart) {
            foreach ($cart->getCartProducts()->getValues() as $cartProduct) {
                $product = $cartProduct->getProduct();

                $orderProduct = new OrderProduct();
                $orderProduct->setAppOrder($order);
                $orderProduct->setQuantity($cartProduct->getQuantity());
                $orderProduct->setPricePerOne($product->getPrice());
                $orderProduct->setProduct($product);

                $order->addOrderProduct($orderProduct);
                $this->entityManager->persist($orderProduct);
            }
        }
    }

    public function createOrderFromCart(Cart $cart, User $user)
    {
        $order = new Order();
        $order->setUser($user);
        $order->setStatus(1);

        $this->addOrderProductsFromCart($order, $cart->getId());
        $this->recalculateOrderTotalPrice($order);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

//        $this->cartManager->remove($cart);
    }

    public function recalculateOrderTotalPrice(Order $order)
    {
        $orderTotalPrice = 0;

        /** @var OrderProduct $orderProduct */
        foreach ($order->getOrderProducts()->getValues() as $orderProduct) {
            $orderTotalPrice += $orderProduct->getQuantity() * $orderProduct->getPricePerOne();
        }

        $order->setTotalPrice($orderTotalPrice);
    }

    public function save(object $entity)
    {
        $entity->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove(object $order)
    {
        $order->setIsDeleted(true);
        $this->save($order);
    }




}