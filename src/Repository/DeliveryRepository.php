<?php

namespace App\Repository;

use App\Entity\Delivery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Delivery>
 *
 * @method Delivery|null find($id, $lockMode = null, $lockVersion = null)
 * @method Delivery|null findOneBy(array $criteria, array $orderBy = null)
 * @method Delivery[]    findAll()
 * @method Delivery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryRepository extends ServiceEntityRepository
{
    public function __construct (ManagerRegistry $registry) {
        parent::__construct($registry, Delivery::class);
    }

    public function save (Delivery $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function lastDeliveryRecorded (): int {
        try {
            return $this->createQueryBuilder('p')
                ->select('p.orderNumber')
                ->orderBy('p.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function totalDispatchesThisMonth (): int | null {
        $date = new \DateTime();
        $date->modify('first day of this month');
        $date->setTime(0, 0, 0);

        try {
            return $this->createQueryBuilder('p')
                ->select('count(p.id)')
                ->where('p.createdAt >= :date')
                ->setParameter('date', $date)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function totalDispatchesToday (): int | null {
        $date = new \DateTime();
        $date->setTime(0, 0, 0);

        try {
            return $this->createQueryBuilder('p')
                ->select('count(p.id)')
                ->where('p.createdAt >= :date')
                ->setParameter('date', $date)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (\Exception $e) {
            return 0.0;
        }
    }

    public function avgEfficiencyThisMonth (): float | null {
        $date = new \DateTime();
        $date->modify('first day of this month');
        $date->setTime(0, 0, 0);

        try {
            return $this->createQueryBuilder('p')
                ->select('avg(p.efficiency)')
                ->where('p.createdAt >= :date')
                ->setParameter('date', $date)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (\Exception $e) {
            return 0.0;
        }
        
    }

    public function avgEfficiencyToday (): float | null {
        $date = new \DateTime();
        $date->setTime(0, 0, 0);

        try {
            return $this->createQueryBuilder('p')
                ->select('avg(p.efficiency)')
                ->where('p.createdAt >= :date')
                ->setParameter('date', $date)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (\Exception $e) {
            return 0.0;
        }
        
    }
}
