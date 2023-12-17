<?php

namespace App\Entity;

use App\Repository\DeliveryRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Delivery\Product as deliveryProduct;

#[ORM\Entity(repositoryClass: DeliveryRepository::class)]
#[ORM\Table(name: 'delivery')]

class Delivery implements \JsonSerializable
{
    #[ ORM\Column(type: 'integer') ]
    #[ ORM\Id ]
    #[ ORM\GeneratedValue ]
    private ?int $id = null;

    #[ORM\Column(name: 'orderNumber', type: 'integer')]
    private ?int $orderNumber = null;
    
    #[ORM\Column(name: 'customerId', type: 'bigint')]
    private ?int $customerId = null;
    
    #[ORM\Column(name: 'createdAt', type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;
    
    #[ORM\Column(name: 'totalRequested', type: 'integer')]
    private ?int $totalRequested = null;
    
    #[ORM\Column(name: 'totalDispatched', type: 'integer')]
    private ?int $totalDispatched = null;
    
    #[ORM\Column(name: 'efficiency', type: 'decimal', precision: 1, scale: 4)]
    private ?float $efficiency = null;
    
    #[ORM\Column(name: 'productsList', type: 'json')]
    
    /**
     * @var deliveryProduct[]
     */
    private ?array $productsList = null;
    
    public function getId (): ?int {
        return $this->id;
    }
    
    public function setId (int $id): self {
        $this->id = $id;
        
        return $this;
    }
    
    public function getOrderNumber (): ?int {
        return $this->orderNumber;
    }
    
    public function setOrderNumber (int $orderNumber): self {
        $this->orderNumber = $orderNumber;
        
        return $this;
    }
    
    public function getCustomerId (): ?int {
        return $this->customerId;
    }
    
    public function setCustomerId (int $customerId): self {
        $this->customerId = $customerId;
        
        return $this;
    }
    
    public function getCreatedAt (): ?\DateTimeInterface {
        return $this->createdAt;
    }
    
    public function setCreatedAt (\DateTimeInterface $createdAt): self {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    public function getTotalRequested (): ?int {
        return $this->totalRequested;
    }
    
    public function setTotalRequested (int $totalRequested): self {
        $this->totalRequested = $totalRequested;
        
        return $this;
    }
    
    public function getTotalDispatched (): ?int {
        return $this->totalDispatched;
    }
    
    public function setTotalDispatched (int $totalDispatched): self {
        $this->totalDispatched = $totalDispatched;
        
        return $this;
    }
    
    public function getEfficiency (): ?float {
        return $this->efficiency;
    }
    
    public function setEfficiency (float $efficiency): self {
        $this->efficiency = $efficiency;
        
        return $this;
    }

    /**
     * @return deliveryProduct[]|null
     */
    public function getProductsList (): ?array {
        return $this->productsList;
    }
    
    public function setProductsList (array $productsList): self {
        
        $this->productsList = $productsList;
        return $this;
    }

    #[\Override] public function jsonSerialize(): array
    {
        
        return [
            'id' => $this->id,
            'orderNumber' => $this->orderNumber,
            'customerId' => $this->customerId,
            'createdAt' => $this->createdAt,
            'totalRequested' => $this->totalRequested,
            'totalDispatched' => $this->totalDispatched,
            'efficiency' => $this->efficiency,
            'productsList' => $this->productsList
        ];
    }
}