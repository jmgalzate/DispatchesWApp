<?php

namespace App\Entity;

use App\Repository\OrderInvoiceSettlementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderInvoiceSettlementRepository::class)]
class OrderInvoiceSettlement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $parcial = null;

    #[ORM\Column(length: 255)]
    private ?string $descuento = null;

    #[ORM\Column(length: 255)]
    private ?string $iva = null;

    #[ORM\Column(length: 255)]
    private ?string $total = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParcial(): ?string
    {
        return $this->parcial;
    }

    public function setParcial(string $parcial): static
    {
        $this->parcial = $parcial;

        return $this;
    }

    public function getDescuento(): ?string
    {
        return $this->descuento;
    }

    public function setDescuento(string $descuento): static
    {
        $this->descuento = $descuento;

        return $this;
    }

    public function getIva(): ?string
    {
        return $this->iva;
    }

    public function setIva(string $iva): static
    {
        $this->iva = $iva;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

        return $this;
    }
}
