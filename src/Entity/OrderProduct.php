<?php

namespace App\Entity;

use App\Repository\OrderProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderProductRepository::class)]
class OrderProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $irecurso = null;

    #[ORM\Column(length: 255)]
    private ?string $itiporec = null;

    #[ORM\Column(length: 255)]
    private ?string $icc = null;

    #[ORM\Column(length: 255)]
    private ?string $sobserv = null;

    #[ORM\Column(length: 255)]
    private ?string $dato1 = null;

    #[ORM\Column(length: 255)]
    private ?string $dato2 = null;

    #[ORM\Column(length: 255)]
    private ?string $dato3 = null;

    #[ORM\Column(length: 255)]
    private ?string $dato4 = null;

    #[ORM\Column(length: 255)]
    private ?string $dato5 = null;

    #[ORM\Column(length: 255)]
    private ?string $dato6 = null;

    #[ORM\Column(length: 255)]
    private ?string $iinventario = null;

    #[ORM\Column(length: 255)]
    private ?string $qrecurso = null;

    #[ORM\Column(length: 255)]
    private ?string $mprecio = null;

    #[ORM\Column(length: 255)]
    private ?string $qporcdescuento = null;

    #[ORM\Column(length: 255)]
    private ?string $qporciva = null;

    #[ORM\Column(length: 255)]
    private ?string $mvrtotal = null;

    #[ORM\Column(length: 255)]
    private ?string $valor1 = null;

    #[ORM\Column(length: 255)]
    private ?string $valor2 = null;

    #[ORM\Column(length: 255)]
    private ?string $valor3 = null;

    #[ORM\Column(length: 255)]
    private ?string $valor4 = null;

    #[ORM\Column(length: 255)]
    private ?string $qrecurso2 = null;

    #[ORM\ManyToOne(inversedBy: 'listaproductos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $orderid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIrecurso(): ?string
    {
        return $this->irecurso;
    }

    public function setIrecurso(string $irecurso): static
    {
        $this->irecurso = $irecurso;

        return $this;
    }

    public function getItiporec(): ?string
    {
        return $this->itiporec;
    }

    public function setItiporec(string $itiporec): static
    {
        $this->itiporec = $itiporec;

        return $this;
    }

    public function getIcc(): ?string
    {
        return $this->icc;
    }

    public function setIcc(string $icc): static
    {
        $this->icc = $icc;

        return $this;
    }

    public function getSobserv(): ?string
    {
        return $this->sobserv;
    }

    public function setSobserv(string $sobserv): static
    {
        $this->sobserv = $sobserv;

        return $this;
    }

    public function getDato1(): ?string
    {
        return $this->dato1;
    }

    public function setDato1(string $dato1): static
    {
        $this->dato1 = $dato1;

        return $this;
    }

    public function getDato2(): ?string
    {
        return $this->dato2;
    }

    public function setDato2(string $dato2): static
    {
        $this->dato2 = $dato2;

        return $this;
    }

    public function getDato3(): ?string
    {
        return $this->dato3;
    }

    public function setDato3(string $dato3): static
    {
        $this->dato3 = $dato3;

        return $this;
    }

    public function getDato4(): ?string
    {
        return $this->dato4;
    }

    public function setDato4(string $dato4): static
    {
        $this->dato4 = $dato4;

        return $this;
    }

    public function getDato5(): ?string
    {
        return $this->dato5;
    }

    public function setDato5(string $dato5): static
    {
        $this->dato5 = $dato5;

        return $this;
    }

    public function getDato6(): ?string
    {
        return $this->dato6;
    }

    public function setDato6(string $dato6): static
    {
        $this->dato6 = $dato6;

        return $this;
    }

    public function getIinventario(): ?string
    {
        return $this->iinventario;
    }

    public function setIinventario(string $iinventario): static
    {
        $this->iinventario = $iinventario;

        return $this;
    }

    public function getQrecurso(): ?string
    {
        return $this->qrecurso;
    }

    public function setQrecurso(string $qrecurso): static
    {
        $this->qrecurso = $qrecurso;

        return $this;
    }

    public function getMprecio(): ?string
    {
        return $this->mprecio;
    }

    public function setMprecio(string $mprecio): static
    {
        $this->mprecio = $mprecio;

        return $this;
    }

    public function getQporcdescuento(): ?string
    {
        return $this->qporcdescuento;
    }

    public function setQporcdescuento(string $qporcdescuento): static
    {
        $this->qporcdescuento = $qporcdescuento;

        return $this;
    }

    public function getQporciva(): ?string
    {
        return $this->qporciva;
    }

    public function setQporciva(string $qporciva): static
    {
        $this->qporciva = $qporciva;

        return $this;
    }

    public function getMvrtotal(): ?string
    {
        return $this->mvrtotal;
    }

    public function setMvrtotal(string $mvrtotal): static
    {
        $this->mvrtotal = $mvrtotal;

        return $this;
    }

    public function getValor1(): ?string
    {
        return $this->valor1;
    }

    public function setValor1(string $valor1): static
    {
        $this->valor1 = $valor1;

        return $this;
    }

    public function getValor2(): ?string
    {
        return $this->valor2;
    }

    public function setValor2(string $valor2): static
    {
        $this->valor2 = $valor2;

        return $this;
    }

    public function getValor3(): ?string
    {
        return $this->valor3;
    }

    public function setValor3(string $valor3): static
    {
        $this->valor3 = $valor3;

        return $this;
    }

    public function getValor4(): ?string
    {
        return $this->valor4;
    }

    public function setValor4(string $valor4): static
    {
        $this->valor4 = $valor4;

        return $this;
    }

    public function getQrecurso2(): ?string
    {
        return $this->qrecurso2;
    }

    public function setQrecurso2(string $qrecurso2): static
    {
        $this->qrecurso2 = $qrecurso2;

        return $this;
    }

    public function getOrderid(): ?Order
    {
        return $this->orderid;
    }

    public function setOrderid(?Order $orderid): static
    {
        $this->orderid = $orderid;

        return $this;
    }
}
