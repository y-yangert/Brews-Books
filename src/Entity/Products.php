<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
#[\Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity('sku_code', message: 'This SKU code is already in use.')]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductCategories $product_categories = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Suppliers $supplier_id = null;

    #[ORM\Column]
    private ?float $cost_per_unit = null;

    #[ORM\Column]
    private ?float $price_per_unit = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $sku_code = null;

    #[ORM\Column]
    private ?int $reorder_level = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToOne(mappedBy: 'product', targetEntity: CoffeeDetails::class)]
    private ?CoffeeDetails $coffeeDetails = null;

    #[ORM\OneToOne(mappedBy: 'product', targetEntity: BookDetails::class)]
    private ?BookDetails $bookDetails = null;

    #[ORM\OneToOne(mappedBy: 'product', targetEntity: Stocks::class)]
    private ?Stocks $stocks = null;

    #[ORM\Column]
    private ?bool $is_active = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getProductCategories(): ?ProductCategories
    {
        return $this->product_categories;
    }

    public function setProductCategories(?ProductCategories $product_categories): static
    {
        $this->product_categories = $product_categories;

        return $this;
    }

    public function getSupplierId(): ?Suppliers
    {
        return $this->supplier_id;
    }

    public function setSupplierId(?Suppliers $supplier_id): static
    {
        $this->supplier_id = $supplier_id;

        return $this;
    }

    public function getCostPerUnit(): ?float
    {
        return $this->cost_per_unit;
    }

    public function setCostPerUnit(float $cost_per_unit): static
    {
        $this->cost_per_unit = $cost_per_unit;

        return $this;
    }

    public function getPricePerUnit(): ?float
    {
        return $this->price_per_unit;
    }

    public function setPricePerUnit(float $price_per_unit): static
    {
        $this->price_per_unit = $price_per_unit;

        return $this;
    }

    public function getSkuCode(): ?string
    {
        return $this->sku_code;
    }

    public function setSkuCode(string $sku_code): static
    {
        $this->sku_code = $sku_code;

        return $this;
    }

    public function getReorderLevel(): ?int
    {
        return $this->reorder_level;
    }

    public function setReorderLevel(int $reorder_level): static
    {
        $this->reorder_level = $reorder_level;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getCoffeeDetails(): ?CoffeeDetails
    {
        return $this->coffeeDetails;
    }

    public function setCoffeeDetails(?CoffeeDetails $coffeeDetails): self
    {
        // set bidirectional relationship
        $this->coffeeDetails = $coffeeDetails;

        // Ensure owning side set properly
        if ($coffeeDetails !== null && $coffeeDetails->getProduct() !== $this) {
            $coffeeDetails->setProduct($this);
        }

        return $this;
    }

    public function getBookDetails(): ?BookDetails
    {
        return $this->bookDetails;
    }

    public function setBookDetails(?BookDetails $bookDetails): self
    {
        // set bidirectional relationship
        $this->bookDetails = $bookDetails;

        // Ensure owning side set properly
        if ($bookDetails !== null && $bookDetails->getProduct() !== $this) {
            $bookDetails->setProduct($this);
        }

        return $this;
    }

    public function getStocks(): ?Stocks
    {
        return $this->stocks;
    }

    public function setStocks(?Stocks $bookDetails): self
    {
        // set bidirectional relationship
        $this->stocks = $stocks;

        // Ensure owning side set properly
        if ($stocks !== null && $stocks->getProduct() !== $this) {
            $stocks->setProduct($this);
        }

        return $this;
    }
}
