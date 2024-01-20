<?php

namespace App\Entity\Order;

class Settlement
{
    public ?string $parcial;
    public ?string $descuento;
    public ?string $iva;
    public ?string $total;
    
    public static function fromArray(array $data): self
    {
        $settlement = new self();
        
        $settlement->parcial = $data['parcial'] ?? null;
        $settlement->descuento = $data['descuento'] ?? null;
        $settlement->iva = $data['iva'] ?? null;
        $settlement->total = $data['total'] ?? null;
        
        return $settlement;
    }
}
