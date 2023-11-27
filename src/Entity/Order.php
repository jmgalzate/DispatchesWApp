<?php

namespace App\Entity;

use App\Entity\Order\MainData;
use App\Entity\Order\Header;
use App\Entity\Order\Settlement;


class Order
{
    private ?Header $encabezado;
    private ?Settlement $liquidacion;
    private ?MainData $datosprincipales;
    
    /** @var array<Product> */
    private array $listaproductos;
    private ?string $qoprsok;
    
    public function getEncabezado(): ?Header
    {
        return $this->encabezado;
    }
    
    public function setEncabezado(?Header $encabezado): self
    {
        $this->encabezado = $encabezado;
        
        return $this;
    }
    
    public function getLiquidacion(): ?Settlement
    {
        return $this->liquidacion;
    }
    
    public function setLiquidacion(?Settlement $liquidacion): self
    {
        $this->liquidacion = $liquidacion;
        
        return $this;
    }
    
    public function getDatosprincipales(): ?MainData
    {
        return $this->datosprincipales;
    }
    
    public function setDatosprincipales(?MainData $datosprincipales): self
    {
        $this->datosprincipales = $datosprincipales;
        
        return $this;
    }
    
    public function getListaproductos(): ?array
    {
        return $this->listaproductos;
    }
    
    public function setListaproductos(array $listaproductos): self
    {
        $this->listaproductos = $listaproductos;
        
        return $this;
    }
    
    public function getQoprsok(): ?string
    {
        return $this->qoprsok;
    }
}
