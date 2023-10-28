<?php

/**
 * TODO: add the rest of the Entities for ContapymeBody
 */

namespace App\Entity;

use App\Repository\ContapymeHeaderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContapymeHeaderRepository::class)]
class ContapymeHeader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $resultado = null;

    #[ORM\Column(length: 255)]
    private ?string $imensaje = null;

    #[ORM\Column(length: 255)]
    private ?string $mensaje = null;

    #[ORM\Column(length: 255)]
    private ?string $tiempo = null;

    #[ORM\Column(length: 255)]
    private ?string $version = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isResultado(): ?bool
    {
        return $this->resultado;
    }

    public function setResultado(bool $resultado): static
    {
        $this->resultado = $resultado;

        return $this;
    }

    public function getImensaje(): ?string
    {
        return $this->imensaje;
    }

    public function setImensaje(string $imensaje): static
    {
        $this->imensaje = $imensaje;

        return $this;
    }

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(string $mensaje): static
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    public function getTiempo(): ?string
    {
        return $this->tiempo;
    }

    public function setTiempo(string $tiempo): static
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }
}
