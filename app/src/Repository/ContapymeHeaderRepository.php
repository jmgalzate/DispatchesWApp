<?php

namespace App\Repository;

use App\Entity\ContapymeHeader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContapymeHeader>
 *
 * @method ContapymeHeader|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContapymeHeader|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContapymeHeader[]    findAll()
 * @method ContapymeHeader[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContapymeHeaderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContapymeHeader::class);
    }

    public function save(ContapymeHeader $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ContapymeHeader $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ContapymeHeader[] Returns an array of ContapymeHeader objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ContapymeHeader
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
