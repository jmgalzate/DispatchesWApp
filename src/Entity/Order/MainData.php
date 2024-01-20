<?php

namespace App\Entity\Order;

class MainData
{
    public ?string $init;
    public ?string $initvendedor;
    public ?string $finicio;
    public ?string $sobserv;
    public ?string $bregvrunit;
    public ?string $bregvrtotal;
    public ?string $condicion1;
    public ?string $icuenta;
    public ?string $blistaconiva;
    public ?string $icccxp;
    public ?string $busarotramoneda;
    public ?string $imonedaimpresion;
    public ?string $ireferencia;
    public ?string $bcerrarref;
    public ?string $qdias;
    public ?string $iinventario;
    public ?string $ilistaprecios;
    public ?string $qporcdescuento;
    public ?string $frmenvio;
    public ?string $frmpago;
    public ?string $mtasacambio;
    public ?string $qregfcobro;
    public ?string $isucursalcliente;
    
    public static function fromArray(array $data): self {
      $mainData = new self();
      
      $mainData->init = $data['init'] ?? null;
      $mainData->initvendedor = $data['initvendedor'] ?? null;
      $mainData->finicio = $data['finicio'] ?? null;
      $mainData->sobserv = $data['sobserv'] ?? null;
      $mainData->bregvrunit = $data['bregvrunit'] ?? null;
      $mainData->bregvrtotal = $data['bregvrtotal'] ?? null;
      $mainData->condicion1 = $data['condicion1'] ?? null;
      $mainData->icuenta = $data['icuenta'] ?? null;
      $mainData->blistaconiva = $data['blistaconiva'] ?? null;
      $mainData->icccxp = $data['icccxp'] ?? null;
      $mainData->busarotramoneda = $data['busarotramoneda'] ?? null;
      $mainData->imonedaimpresion = $data['imonedaimpresion'] ?? null;
      $mainData->ireferencia = $data['ireferencia'] ?? null;
      $mainData->bcerrarref = $data['bcerrarref'] ?? null;
      $mainData->qdias = $data['qdias'] ?? null;
      $mainData->iinventario = $data['iinventario'] ?? null;
      $mainData->ilistaprecios = $data['ilistaprecios'] ?? null;
      $mainData->qporcdescuento = $data['qporcdescuento'] ?? null;
      $mainData->frmenvio = $data['frmenvio'] ?? null;
      $mainData->frmpago = $data['frmpago'] ?? null;
      $mainData->mtasacambio = $data['mtasacambio'] ?? null;
      $mainData->qregfcobro = $data['qregfcobro'] ?? null;
      $mainData->isucursalcliente = $data['isucursalcliente'] ?? null;
      
      return $mainData;
      
    }
}
