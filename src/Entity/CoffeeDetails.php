<?php

namespace App\Entity;

use App\Repository\CoffeeDetailsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoffeeDetailsRepository::class)]
class CoffeeDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "product_id", nullable: false)]
    private ?Products $product = null;


    #[ORM\Column(length: 255)]
    private ?string $roast_level = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $weight_per_package = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $flavor_description = null;

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


    public function getRoastLevel(): ?string
    {
        return $this->roast_level;
    }

    public function setRoastLevel(string $roast_level): static
    {
        $this->roast_level = $roast_level;

        return $this;
    }

    public function getWeightPerPackage(): ?string
    {
        return $this->weight_per_package;
    }

    public function setWeightPerPackage(string $weight_per_package): static
    {
        $this->weight_per_package = $weight_per_package;

        return $this;
    }

    public function getFlavorDescription(): ?string
    {
        return $this->flavor_description;
    }

    public function setFlavorDescription(?string $flavor_description): static
    {
        $this->flavor_description = $flavor_description;

        return $this;
    }
}
