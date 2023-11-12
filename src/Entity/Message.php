<?php
namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Table(name: 'message')]

class Message {
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;
    
    #[ORM\Column(name: 'message_type', type: 'integer')]
    private ?int $messageType = null;

    #[ORM\Column(name: 'order_number', type: 'integer')]
    private ?int $orderNumber = null;
    
    #[ORM\Column(name: 'payload', type: 'TEXT')]
    private ?string $payload = null;
    
    #[ORM\Column(name: 'response', type: 'TEXT')]
    private ?string $response = null;
    
    #[ORM\Column(name: 'created_at', type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;
    
    public function getId(): ?int {
        return $this->id;
    }

    public function getMessageType(): ?int {
        return $this->messageType;
    }

    public function setMessageType(int $messageType): self {
        $this->messageType = $messageType;
        return $this;
    }

    public function getOrderNumber(): ?int {
        return $this->orderNumber;
    }

    public function setOrderNumber(int $orderNumber): self {
        $this->orderNumber = $orderNumber;
        return $this;
    }

    public function getPayload(): ?string {
        return $this->payload;
    }

    public function setPayload(string $payload): self {
        $this->payload = $payload;
        return $this;
    }

    public function getResponse(): ?string {
        return $this->response;
    }

    public function setResponse(string $response): self {
        $this->response = $response;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self {
        $this->createdAt = $createdAt;
        return $this;
    }
    
}
