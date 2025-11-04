<?php

namespace App\Entity;

use DateTime;
use App\Repository\StocksRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StocksRepository::class)]
class Stocks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Products $product = null;

    #[ORM\Column]
    private ?int $quantity_in_stock = null;

    #[ORM\Column]
    private ?\DateTime $last_restock_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(Products $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantityInStock(): ?int
    {
        return $this->quantity_in_stock;
    }

    public function setQuantityInStock(int $quantity_in_stock): static
    {
        $this->quantity_in_stock = $quantity_in_stock;

        return $this;
    }

    public function __construct()
    {
        $this->last_restock_date = new DateTime();
    }

    public function getLastRestockDate(): ?\DateTime
    {
        return $this->last_restock_date;
    }

    public function setLastRestockDate(\DateTime $last_restock_date): static
    {
        $this->last_restock_date = $last_restock_date;

        return $this;
    }
}
