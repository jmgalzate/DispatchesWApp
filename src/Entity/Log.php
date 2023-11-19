<?php 

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogRepository::class)]
#[ORM\Table(name: 'log')]

class Log 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    //0. Error, 1. Warning, 2. Info
    #[ORM\Column(name: 'logType', type: 'integer')]
    private ?int $logType = null;

    #[ORM\Column(name: 'logDetails', type: 'TEXT')]
    private ?string $logDetails = null;

    #[ORM\Column(name: 'createdAt', type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getLogType(): ?int {
        return $this->logType;
    }

    public function setLogType(int $logType): self {
        $this->logType = $logType;
        return $this;
    }

    public function getLogDetails(): ?string {
        return $this->logDetails;
    }

    public function setLogDetails(string $logDetails): self {
        $this->logDetails = $logDetails;
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