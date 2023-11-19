<?php

namespace App\Entity\Contapyme;

class Order
{
    public ?int $id = null;
    public ?OrderHeader $encabezado = null;
    public ?OrderInvoiceSettlement $liquidacion = null;
    public ?OrderMainData $datosprincipales = null;
    public array $listaproductos;
    public ?string $qoprsok = null;
}
