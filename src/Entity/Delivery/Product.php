<?php

namespace App\Entity\Delivery;

use App\Entity\Product as ProductEntity;

class Product extends ProductEntity implements \JsonSerializable
{
    
    private ?int $requestedQuantity = null;
    private ?int $deliveredQuantity = null;
    
    public function __construct (
        string $name,
        string $barcode,
        string $code,
        int $requestedQuantity,
        int $deliveredQuantity
    ) {
        parent::__construct($name, $barcode, $code);
        $this->requestedQuantity = $requestedQuantity;
        $this->deliveredQuantity = $deliveredQuantity;
    }
    
    public function getRequestedQuantity(): ?int
    {
        return $this->requestedQuantity;
    }
    
    public function setRequestedQuantity(?int $requestedQuantity): self
    {
        $this->requestedQuantity = $requestedQuantity;
        
        return $this;
    }
    
    public function getDeliveredQuantity(): ?int
    {
        return $this->deliveredQuantity;
    }
    
    public function setDeliveredQuantity(?int $deliveredQuantity): self
    {
        $this->deliveredQuantity = $deliveredQuantity;
        
        return $this;
    }

    #[\Override] public function jsonSerialize (): array {
        return [
            'name' => $this->getName(),
            'barcode' => $this->getBarcode(),
            'code' => $this->getCode(),
            'requestedQuantity' => $this->getRequestedQuantity(),
            'deliveredQuantity' => $this->getDeliveredQuantity()
        ];
    }
}