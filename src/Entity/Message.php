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
    
    #[ORM\Column(name: 'endpoint', type: 'string', length: 255)]
    private ?string $endpoint = null;
    
    #[ORM\Column(name: 'http_status', type: 'integer')]
    private ?int $httpStatus = null;
    
    #[ORM\Column(name: 'payload', type: 'json')]
    private $payload = null;
    
    #[ORM\Column(name: 'response', type: 'json')]
    private $response = null;
    
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

    public function setOrderNumber(int $orderNumber = null): self {
        $this->orderNumber = $orderNumber;
        return $this;
    }
    
    public function getEndpoint(): ?string {
        return $this->endpoint;
    }
    
    public function setEndpoint(string $endpoint): self {
        $this->endpoint = $endpoint;
        return $this;
    }
    
    public function getHttpStatus(): ?int {
        return $this->httpStatus;
    }
    
    public function setHttpStatus(int $httpStatus): self {
        $this->httpStatus = $httpStatus;
        return $this;
    }
    
    public function getPayload() {
        return $this->payload;
    }

    public function setPayload($payload): self {
        $this->payload = $payload;
        return $this;
    }

    public function getResponse() {
        return $this->response;
    }

    public function setResponse($response): self {
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