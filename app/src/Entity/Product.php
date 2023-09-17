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
    private ?string $code = null;

    #[ORM\Column]
    private ?int $requestedquantity = null;

    #[ORM\Column]
    private ?int $dispatchedquantity = null;

    public function __construct(
        string $name,
        string $barcode,
        string $code,
        int $requestedquantity,
        int $dispatchedquantity
    ) {
        $this->name = $name;
        $this->barcode = $barcode;
        $this->code = $code;
        $this->requestedquantity = $requestedquantity;
        $this->dispatchedquantity = $dispatchedquantity;
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getRequestedquantity(): ?int
    {
        return $this->requestedquantity;
    }

    public function setRequestedquantity(int $requestedquantity): static
    {
        $this->requestedquantity = $requestedquantity;

        return $this;
    }

    public function getDispatchedquantity(): ?int
    {
        return $this->dispatchedquantity;
    }

    public function setDispatchedquantity(int $dispatchedquantity): static
    {
        $this->dispatchedquantity = $dispatchedquantity;

        return $this;
    }
}
