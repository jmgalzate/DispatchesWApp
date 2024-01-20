<?php

namespace App\Entity\Order;

class Header
{
    public ?string $tdetalle;
    public ?string $itdoper;
    public ?string $snumsop;
    public ?string $fsoport;
    public ?string $iccbase;
    public ?string $imoneda;
    public ?string $banulada;
    public ?string $blocal;
    public ?string $bniif;
    public ?string $svaloradic1;
    public ?string $svaloradic2;
    public ?string $svaloradic3;
    public ?string $svaloradic4;
    public ?string $svaloradic5;
    public ?string $svaloradic6;
    public ?string $svaloradic7;
    public ?string $svaloradic8;
    public ?string $svaloradic9;
    public ?string $svaloradic10;
    public ?string $svaloradic11;
    public ?string $svaloradic12;
    public ?string $fecha1adic;
    public ?string $fecha2adic;
    public ?string $fecha3adic;
    public ?string $datosaddin;
    public ?string $fcreacion;
    public ?string $fultima;
    public ?string $fprocesam;
    public ?string $iusuario;
    public ?string $iusuarioult;
    public ?string $isucursal;
    public ?string $inumoperultimp;
    public ?string $accionesalgrabar;
    public ?string $iemp;
    public ?string $inumoper;
    public ?string $itdsop;
    public ?string $inumsop;
    public ?string $iclasifop;
    public ?string $iprocess;
    public ?string $mtotaloperacion;
    
    public static function fromArray (array $data): self {
      
      $header = new self();
      
      $header->tdetalle = $data['tdetalle'] ?? null;
      $header->itdoper = $data['itdoper'] ?? null;
      $header->snumsop = $data['snumsop'] ?? null;
      $header->fsoport = $data['fsoport'] ?? null;
      $header->iccbase = $data['iccbase'] ?? null;
      $header->imoneda = $data['imoneda'] ?? null;
      $header->banulada = $data['banulada'] ?? null;
      $header->blocal = $data['blocal'] ?? null;
      $header->bniif = $data['bniif'] ?? null;
      $header->svaloradic1 = $data['svaloradic1'] ?? null;
      $header->svaloradic2 = $data['svaloradic2'] ?? null;
      $header->svaloradic3 = $data['svaloradic3'] ?? null;
      $header->svaloradic4 = $data['svaloradic4'] ?? null;
      $header->svaloradic5 = $data['svaloradic5'] ?? null;
      $header->svaloradic6 = $data['svaloradic6'] ?? null;
      $header->svaloradic7 = $data['svaloradic7'] ?? null;
      $header->svaloradic8 = $data['svaloradic8'] ?? null;
      $header->svaloradic9 = $data['svaloradic9'] ?? null;
      $header->svaloradic10 = $data['svaloradic10'] ?? null;
      $header->svaloradic11 = $data['svaloradic11'] ?? null;
      $header->svaloradic12 = $data['svaloradic12'] ?? null;
      $header->fecha1adic = $data['fecha1adic'] ?? null;
      $header->fecha2adic = $data['fecha2adic'] ?? null;
      $header->fecha3adic = $data['fecha3adic'] ?? null;
      $header->datosaddin = $data['datosaddin'] ?? null;
      $header->fcreacion = $data['fcreacion'] ?? null;
      $header->fultima = $data['fultima'] ?? null;
      $header->fprocesam = $data['fprocesam'] ?? null;
      $header->iusuario = $data['iusuario'] ?? null;
      $header->iusuarioult = $data['iusuarioult'] ?? null;
      $header->isucursal = $data['isucursal'] ?? null;
      $header->inumoperultimp = $data['inumoperultimp'] ?? null;
      $header->accionesalgrabar = $data['accionesalgrabar'] ?? null;
      $header->iemp = $data['iemp'] ?? null;
      $header->inumoper = $data['inumoper'] ?? null;
      $header->itdsop = $data['itdsop'] ?? null;
      $header->inumsop = $data['inumsop'] ?? null;
      $header->iclasifop = $data['iclasifop'] ?? null;
      $header->iprocess = $data['iprocess'] ?? null;
      $header->mtotaloperacion = $data['mtotaloperacion'] ?? null;
      
      return $header;
    }
}
