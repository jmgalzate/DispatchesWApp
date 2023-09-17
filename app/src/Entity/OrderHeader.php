<?php

namespace App\Entity;

use App\Repository\OrderHeaderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderHeaderRepository::class)]
class OrderHeader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $tdetalle = null;

    #[ORM\Column(length: 255)]
    private ?string $itdoper = null;

    #[ORM\Column(length: 255)]
    private ?string $snumsop = null;

    #[ORM\Column(length: 255)]
    private ?string $fsoport = null;

    #[ORM\Column(length: 255)]
    private ?string $iccbase = null;

    #[ORM\Column(length: 255)]
    private ?string $imoneda = null;

    #[ORM\Column(length: 255)]
    private ?string $banulada = null;

    #[ORM\Column(length: 255)]
    private ?string $blocal = null;

    #[ORM\Column(length: 255)]
    private ?string $bniif = null;

    #[ORM\Column(length: 255)]
    private ?string $svaloradic1 = null;

    #[ORM\Column(length: 255)]
    private ?string $svaloradic2 = null;

    #[ORM\Column(length: 255)]
    private ?string $svaloradic3 = null;

    #[ORM\Column(length: 255)]
    private ?string $svaloradic4 = null;

    #[ORM\Column(length: 255)]
    private ?string $svaloradic5 = null;

    #[ORM\Column(length: 255)]
    private ?string $svaloradic6 = null;

    #[ORM\Column(length: 255)]
    private ?string $svaloradic7 = null;

    #[ORM\Column(length: 255)]
    private ?string $svaloradic8 = null;

    #[ORM\Column(length: 255)]
    private ?string $svaloradic9 = null;

    #[ORM\Column(length: 255)]
    private ?string $svaloradic10 = null;

    #[ORM\Column(length: 255)]
    private ?string $svaloradic11 = null;

    #[ORM\Column(length: 255)]
    private ?string $svaloradic12 = null;

    #[ORM\Column(length: 255)]
    private ?string $fecha1adic = null;

    #[ORM\Column(length: 255)]
    private ?string $fecha2adic = null;

    #[ORM\Column(length: 255)]
    private ?string $fecha3adic = null;

    #[ORM\Column(length: 255)]
    private ?string $datosaddin = null;

    #[ORM\Column(length: 255)]
    private ?string $fcreacion = null;

    #[ORM\Column(length: 255)]
    private ?string $fultima = null;

    #[ORM\Column(length: 255)]
    private ?string $fprocesam = null;

    #[ORM\Column(length: 255)]
    private ?string $iusuario = null;

    #[ORM\Column(length: 255)]
    private ?string $iusuarioult = null;

    #[ORM\Column(length: 255)]
    private ?string $isucursal = null;

    #[ORM\Column(length: 255)]
    private ?string $inumoperultimp = null;

    #[ORM\Column(length: 255)]
    private ?string $accionesalgrabar = null;

    #[ORM\Column(length: 255)]
    private ?string $iemp = null;

    #[ORM\Column(length: 255)]
    private ?string $inumoper = null;

    #[ORM\Column(length: 255)]
    private ?string $itdsop = null;

    #[ORM\Column(length: 255)]
    private ?string $inumsop = null;

    #[ORM\Column(length: 255)]
    private ?string $iclasifop = null;

    #[ORM\Column(length: 255)]
    private ?string $iprocess = null;

    #[ORM\Column(length: 255)]
    private ?string $mtotaloperacion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTdetalle(): ?string
    {
        return $this->tdetalle;
    }

    public function setTdetalle(string $tdetalle): static
    {
        $this->tdetalle = $tdetalle;

        return $this;
    }

    public function getItdoper(): ?string
    {
        return $this->itdoper;
    }

    public function setItdoper(string $itdoper): static
    {
        $this->itdoper = $itdoper;

        return $this;
    }

    public function getSnumsop(): ?string
    {
        return $this->snumsop;
    }

    public function setSnumsop(string $snumsop): static
    {
        $this->snumsop = $snumsop;

        return $this;
    }

    public function getFsoport(): ?string
    {
        return $this->fsoport;
    }

    public function setFsoport(string $fsoport): static
    {
        $this->fsoport = $fsoport;

        return $this;
    }

    public function getIccbase(): ?string
    {
        return $this->iccbase;
    }

    public function setIccbase(string $iccbase): static
    {
        $this->iccbase = $iccbase;

        return $this;
    }

    public function getImoneda(): ?string
    {
        return $this->imoneda;
    }

    public function setImoneda(string $imoneda): static
    {
        $this->imoneda = $imoneda;

        return $this;
    }

    public function getBanulada(): ?string
    {
        return $this->banulada;
    }

    public function setBanulada(string $banulada): static
    {
        $this->banulada = $banulada;

        return $this;
    }

    public function getBlocal(): ?string
    {
        return $this->blocal;
    }

    public function setBlocal(string $blocal): static
    {
        $this->blocal = $blocal;

        return $this;
    }

    public function getBniif(): ?string
    {
        return $this->bniif;
    }

    public function setBniif(string $bniif): static
    {
        $this->bniif = $bniif;

        return $this;
    }

    public function getSvaloradic1(): ?string
    {
        return $this->svaloradic1;
    }

    public function setSvaloradic1(string $svaloradic1): static
    {
        $this->svaloradic1 = $svaloradic1;

        return $this;
    }

    public function getSvaloradic2(): ?string
    {
        return $this->svaloradic2;
    }

    public function setSvaloradic2(string $svaloradic2): static
    {
        $this->svaloradic2 = $svaloradic2;

        return $this;
    }

    public function getSvaloradic3(): ?string
    {
        return $this->svaloradic3;
    }

    public function setSvaloradic3(string $svaloradic3): static
    {
        $this->svaloradic3 = $svaloradic3;

        return $this;
    }

    public function getSvaloradic4(): ?string
    {
        return $this->svaloradic4;
    }

    public function setSvaloradic4(string $svaloradic4): static
    {
        $this->svaloradic4 = $svaloradic4;

        return $this;
    }

    public function getSvaloradic5(): ?string
    {
        return $this->svaloradic5;
    }

    public function setSvaloradic5(string $svaloradic5): static
    {
        $this->svaloradic5 = $svaloradic5;

        return $this;
    }

    public function getSvaloradic6(): ?string
    {
        return $this->svaloradic6;
    }

    public function setSvaloradic6(string $svaloradic6): static
    {
        $this->svaloradic6 = $svaloradic6;

        return $this;
    }

    public function getSvaloradic7(): ?string
    {
        return $this->svaloradic7;
    }

    public function setSvaloradic7(string $svaloradic7): static
    {
        $this->svaloradic7 = $svaloradic7;

        return $this;
    }

    public function getSvaloradic8(): ?string
    {
        return $this->svaloradic8;
    }

    public function setSvaloradic8(string $svaloradic8): static
    {
        $this->svaloradic8 = $svaloradic8;

        return $this;
    }

    public function getSvaloradic9(): ?string
    {
        return $this->svaloradic9;
    }

    public function setSvaloradic9(string $svaloradic9): static
    {
        $this->svaloradic9 = $svaloradic9;

        return $this;
    }

    public function getSvaloradic10(): ?string
    {
        return $this->svaloradic10;
    }

    public function setSvaloradic10(string $svaloradic10): static
    {
        $this->svaloradic10 = $svaloradic10;

        return $this;
    }

    public function getSvaloradic11(): ?string
    {
        return $this->svaloradic11;
    }

    public function setSvaloradic11(string $svaloradic11): static
    {
        $this->svaloradic11 = $svaloradic11;

        return $this;
    }

    public function getSvaloradic12(): ?string
    {
        return $this->svaloradic12;
    }

    public function setSvaloradic12(string $svaloradic12): static
    {
        $this->svaloradic12 = $svaloradic12;

        return $this;
    }

    public function getFecha1adic(): ?string
    {
        return $this->fecha1adic;
    }

    public function setFecha1adic(string $fecha1adic): static
    {
        $this->fecha1adic = $fecha1adic;

        return $this;
    }

    public function getFecha2adic(): ?string
    {
        return $this->fecha2adic;
    }

    public function setFecha2adic(string $fecha2adic): static
    {
        $this->fecha2adic = $fecha2adic;

        return $this;
    }

    public function getFecha3adic(): ?string
    {
        return $this->fecha3adic;
    }

    public function setFecha3adic(string $fecha3adic): static
    {
        $this->fecha3adic = $fecha3adic;

        return $this;
    }

    public function getDatosaddin(): ?string
    {
        return $this->datosaddin;
    }

    public function setDatosaddin(string $datosaddin): static
    {
        $this->datosaddin = $datosaddin;

        return $this;
    }

    public function getFcreacion(): ?string
    {
        return $this->fcreacion;
    }

    public function setFcreacion(string $fcreacion): static
    {
        $this->fcreacion = $fcreacion;

        return $this;
    }

    public function getFultima(): ?string
    {
        return $this->fultima;
    }

    public function setFultima(string $fultima): static
    {
        $this->fultima = $fultima;

        return $this;
    }

    public function getFprocesam(): ?string
    {
        return $this->fprocesam;
    }

    public function setFprocesam(string $fprocesam): static
    {
        $this->fprocesam = $fprocesam;

        return $this;
    }

    public function getIusuario(): ?string
    {
        return $this->iusuario;
    }

    public function setIusuario(string $iusuario): static
    {
        $this->iusuario = $iusuario;

        return $this;
    }

    public function getIusuarioult(): ?string
    {
        return $this->iusuarioult;
    }

    public function setIusuarioult(string $iusuarioult): static
    {
        $this->iusuarioult = $iusuarioult;

        return $this;
    }

    public function getIsucursal(): ?string
    {
        return $this->isucursal;
    }

    public function setIsucursal(string $isucursal): static
    {
        $this->isucursal = $isucursal;

        return $this;
    }

    public function getInumoperultimp(): ?string
    {
        return $this->inumoperultimp;
    }

    public function setInumoperultimp(string $inumoperultimp): static
    {
        $this->inumoperultimp = $inumoperultimp;

        return $this;
    }

    public function getAccionesalgrabar(): ?string
    {
        return $this->accionesalgrabar;
    }

    public function setAccionesalgrabar(string $accionesalgrabar): static
    {
        $this->accionesalgrabar = $accionesalgrabar;

        return $this;
    }

    public function getIemp(): ?string
    {
        return $this->iemp;
    }

    public function setIemp(string $iemp): static
    {
        $this->iemp = $iemp;

        return $this;
    }

    public function getInumoper(): ?string
    {
        return $this->inumoper;
    }

    public function setInumoper(string $inumoper): static
    {
        $this->inumoper = $inumoper;

        return $this;
    }

    public function getItdsop(): ?string
    {
        return $this->itdsop;
    }

    public function setItdsop(string $itdsop): static
    {
        $this->itdsop = $itdsop;

        return $this;
    }

    public function getInumsop(): ?string
    {
        return $this->inumsop;
    }

    public function setInumsop(string $inumsop): static
    {
        $this->inumsop = $inumsop;

        return $this;
    }

    public function getIclasifop(): ?string
    {
        return $this->iclasifop;
    }

    public function setIclasifop(string $iclasifop): static
    {
        $this->iclasifop = $iclasifop;

        return $this;
    }

    public function getIprocess(): ?string
    {
        return $this->iprocess;
    }

    public function setIprocess(string $iprocess): static
    {
        $this->iprocess = $iprocess;

        return $this;
    }

    public function getMtotaloperacion(): ?string
    {
        return $this->mtotaloperacion;
    }

    public function setMtotaloperacion(string $mtotaloperacion): static
    {
        $this->mtotaloperacion = $mtotaloperacion;

        return $this;
    }
}
