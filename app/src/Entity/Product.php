<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $barcode = null;

    #[ORM\Column(length: 255)]
    private ?string $productcode = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function __construct(
        int $id,
        string $name,
        string $barcode,
        string $productcode,
        int $quantity
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->barcode = $barcode;
        $this->productcode = $productcode;
        $this->quantity = $quantity;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }
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

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): static
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getProductcode(): ?string
    {
        return $this->productcode;
    }

    public function setProductcode(string $productcode): static
    {
        $this->productcode = $productcode;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}
