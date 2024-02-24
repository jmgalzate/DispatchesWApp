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
  private ?int $id;

  //0. Error, 1. Warning, 2. Info
  #[ORM\Column(name: 'log_type', type: 'integer')]
  private ?int $logType = null;

  #[ORM\Column(name: 'log_details', type: 'json')] //Changed type to 'json'
  private $logDetails = null;

  #[ORM\Column(name: 'created_at', type: 'datetime')]
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

  public function getLogDetails() { //Removed return type
    return $this->logDetails;
  }

  public function setLogDetails($logDetails): self { //Removed parameter type
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