<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $session_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartProduct::class, orphanRemoval: true)]
    private Collection $cartProducts;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->cartProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSessionId(): ?string
    {
        return $this->session_id;
    }

    public function setSessionId(string $session_id): self
    {
        $this->session_id = $session_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, CartProduct>
     */
    public function getCartProducts(): Collection
    {
        return $this->cartProducts;
    }

    public function addCartProduct(CartProduct $cartProduct): self
    {
        if (!$this->cartProducts->contains($cartProduct)) {
            $this->cartProducts->add($cartProduct);
            $cartProduct->setCart($this);
        }

        return $this;
    }

    public function removeCartProduct(CartProduct $cartProduct): self
    {
        if ($this->cartProducts->removeElement($cartProduct)) {
            // set the owning side to null (unless already changed)
            if ($cartProduct->getCart() === $this) {
                $cartProduct->setCart(null);
            }
        }

        return $this;
    }
}
