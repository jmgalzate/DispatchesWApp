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

    #[ORM\Column(name: 'name', length: 255)]
    private ?string $name = null;

    #[ORM\Column(name: 'barcode',length: 255)]
    private ?string $barcode = null;

    #[ORM\Column(name: 'code', length: 255)]
    private ?string $code = null;

    public function __construct (
        string  $name,
        string  $barcode,
        string  $code
    ) {
        $this->name = $name;
        $this->barcode = $barcode;
        $this->code = $code;
    }

    public function getId (): ?int {
        return $this->id;
    }

    public function getName (): ?string {
        return $this->name;
    }

    public function setName (string $name): static {
        $this->name = $name;

        return $this;
    }

    public function getBarcode (): ?string {
        return $this->barcode;
    }

    public function setBarcode (string $barcode): self {
        $this->barcode = $barcode;

        return $this;
    }

    public function getCode (): ?string {
        return $this->code;
    }

    public function setCode (string $code): self {
        $this->code = $code;

        return $this;
    }
}
