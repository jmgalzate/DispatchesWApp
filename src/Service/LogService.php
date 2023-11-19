<?php

namespace App\Service;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;

class LogService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function recordLog(int $logType, array $logDetails): int
    {
            $log = new Log();

            $log->setCreatedAt(new \DateTime());
            $log->setLogType($logType);
            $log->setLogDetails(json_encode($logDetails));

            $this->entityManager->persist($log);
            $this->entityManager->flush();
            
            return $log->getId();
    }
}