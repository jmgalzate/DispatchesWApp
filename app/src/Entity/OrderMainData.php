<?php

namespace App\Entity;

use App\Repository\OrderMainDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderMainDataRepository::class)]
class OrderMainData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $init = null;

    #[ORM\Column(length: 255)]
    private ?string $initvendedor = null;

    #[ORM\Column(length: 255)]
    private ?string $finicio = null;

    #[ORM\Column(length: 255)]
    private ?string $sobserv = null;

    #[ORM\Column(length: 255)]
    private ?string $bregvrunit = null;

    #[ORM\Column(length: 255)]
    private ?string $bregvrtotal = null;

    #[ORM\Column(length: 255)]
    private ?string $condicion1 = null;

    #[ORM\Column(length: 255)]
    private ?string $icuenta = null;

    #[ORM\Column(length: 255)]
    private ?string $blistaconiva = null;

    #[ORM\Column(length: 255)]
    private ?string $icccxp = null;

    #[ORM\Column(length: 255)]
    private ?string $busarotramoneda = null;

    #[ORM\Column(length: 255)]
    private ?string $imonedaimpresion = null;

    #[ORM\Column(length: 255)]
    private ?string $ireferencia = null;

    #[ORM\Column(length: 255)]
    private ?string $bcerrarref = null;

    #[ORM\Column(length: 255)]
    private ?string $qdias = null;

    #[ORM\Column(length: 255)]
    private ?string $iinventario = null;

    #[ORM\Column(length: 255)]
    private ?string $ilistaprecios = null;

    #[ORM\Column(length: 255)]
    private ?string $qporcdescuento = null;

    #[ORM\Column(length: 255)]
    private ?string $frmenvio = null;

    #[ORM\Column(length: 255)]
    private ?string $frmpago = null;

    #[ORM\Column(length: 255)]
    private ?string $mtasacambio = null;

    #[ORM\Column(length: 255)]
    private ?string $qregfcobro = null;

    #[ORM\Column(length: 255)]
    private ?string $isucursalcliente = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInit(): ?string
    {
        return $this->init;
    }

    public function setInit(string $init): static
    {
        $this->init = $init;

        return $this;
    }

    public function getInitvendedor(): ?string
    {
        return $this->initvendedor;
    }

    public function setInitvendedor(string $initvendedor): static
    {
        $this->initvendedor = $initvendedor;

        return $this;
    }

    public function getFinicio(): ?string
    {
        return $this->finicio;
    }

    public function setFinicio(string $finicio): static
    {
        $this->finicio = $finicio;

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

    public function getBregvrunit(): ?string
    {
        return $this->bregvrunit;
    }

    public function setBregvrunit(string $bregvrunit): static
    {
        $this->bregvrunit = $bregvrunit;

        return $this;
    }

    public function getBregvrtotal(): ?string
    {
        return $this->bregvrtotal;
    }

    public function setBregvrtotal(string $bregvrtotal): static
    {
        $this->bregvrtotal = $bregvrtotal;

        return $this;
    }

    public function getCondicion1(): ?string
    {
        return $this->condicion1;
    }

    public function setCondicion1(string $condicion1): static
    {
        $this->condicion1 = $condicion1;

        return $this;
    }

    public function getIcuenta(): ?string
    {
        return $this->icuenta;
    }

    public function setIcuenta(string $icuenta): static
    {
        $this->icuenta = $icuenta;

        return $this;
    }

    public function getBlistaconiva(): ?string
    {
        return $this->blistaconiva;
    }

    public function setBlistaconiva(string $blistaconiva): static
    {
        $this->blistaconiva = $blistaconiva;

        return $this;
    }

    public function getIcccxp(): ?string
    {
        return $this->icccxp;
    }

    public function setIcccxp(string $icccxp): static
    {
        $this->icccxp = $icccxp;

        return $this;
    }

    public function getBusarotramoneda(): ?string
    {
        return $this->busarotramoneda;
    }

    public function setBusarotramoneda(string $busarotramoneda): static
    {
        $this->busarotramoneda = $busarotramoneda;

        return $this;
    }

    public function getImonedaimpresion(): ?string
    {
        return $this->imonedaimpresion;
    }

    public function setImonedaimpresion(string $imonedaimpresion): static
    {
        $this->imonedaimpresion = $imonedaimpresion;

        return $this;
    }

    public function getIreferencia(): ?string
    {
        return $this->ireferencia;
    }

    public function setIreferencia(string $ireferencia): static
    {
        $this->ireferencia = $ireferencia;

        return $this;
    }

    public function getBcerrarref(): ?string
    {
        return $this->bcerrarref;
    }

    public function setBcerrarref(string $bcerrarref): static
    {
        $this->bcerrarref = $bcerrarref;

        return $this;
    }

    public function getQdias(): ?string
    {
        return $this->qdias;
    }

    public function setQdias(string $qdias): static
    {
        $this->qdias = $qdias;

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

    public function getIlistaprecios(): ?string
    {
        return $this->ilistaprecios;
    }

    public function setIlistaprecios(string $ilistaprecios): static
    {
        $this->ilistaprecios = $ilistaprecios;

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

    public function getFrmenvio(): ?string
    {
        return $this->frmenvio;
    }

    public function setFrmenvio(string $frmenvio): static
    {
        $this->frmenvio = $frmenvio;

        return $this;
    }

    public function getFrmpago(): ?string
    {
        return $this->frmpago;
    }

    public function setFrmpago(string $frmpago): static
    {
        $this->frmpago = $frmpago;

        return $this;
    }

    public function getMtasacambio(): ?string
    {
        return $this->mtasacambio;
    }

    public function setMtasacambio(string $mtasacambio): static
    {
        $this->mtasacambio = $mtasacambio;

        return $this;
    }

    public function getQregfcobro(): ?string
    {
        return $this->qregfcobro;
    }

    public function setQregfcobro(string $qregfcobro): static
    {
        $this->qregfcobro = $qregfcobro;

        return $this;
    }

    public function getIsucursalcliente(): ?string
    {
        return $this->isucursalcliente;
    }

    public function setIsucursalcliente(string $isucursalcliente): static
    {
        $this->isucursalcliente = $isucursalcliente;

        return $this;
    }
}
