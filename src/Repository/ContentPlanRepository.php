<?php

namespace App\Repository;

use App\Entity\ContentPlan;
use App\Entity\User;
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
    public function findTodayContentPlans(): array
    {
        $today = new \DateTime('today');

        return $this->createQueryBuilder('c')
            ->leftJoin('c.project', 'p')
            ->andWhere('c.date = :today')
            ->andWhere('p.deletedBy IS NULL')
            ->andWhere('p.isActive = true')
            ->setParameter('today', $today)
            ->orderBy('c.position', 'ASC') // Optional: order by position
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
