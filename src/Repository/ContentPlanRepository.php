<?php

namespace App\Repository;

use App\Entity\ContentPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContentPlan>
 */
class ContentPlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContentPlan::class);
    }

    /**
     * @return ContentPlan[]
     */
    public function findContentPlansByDateRange(\DateTimeInterface $from, \DateTimeInterface $to): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.project', 'p')
            ->andWhere('c.date >= :from')
            ->andWhere('c.date <= :to')
            ->andWhere('c.deletedBy IS NULL')
            ->andWhere('p.deletedBy IS NULL')
            ->andWhere('p.isActive = true')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->orderBy('c.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return ContentPlan[] Returns an array of ContentPlan objects
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

    //    public function findOneBySomeField($value): ?ContentPlan
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
