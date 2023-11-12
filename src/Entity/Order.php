<?php

namespace App\Entity;
class Order
{
    private ?int $id = null;
    private ?OrderHeader $encabezado = null;
    private ?OrderInvoiceSettlement $liquidacion = null;
    private ?OrderMainData $datosprincipales = null;
    private array $listaproductos;
    private ?string $qoprsok = null;
}
