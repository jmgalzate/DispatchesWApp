<?php

namespace App\Entity\Order;

class Settlement
{
    public ?string $parcial;
    public ?string $descuento;
    public ?string $iva;
    public ?string $total;
}
