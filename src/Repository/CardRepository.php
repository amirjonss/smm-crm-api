<?php

namespace App\Repository;

use App\Entity\BoardList;
use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Card>
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    public function findMinCardPositionNumberInList(BoardList $boardList): int
    {
        return $this->createQueryBuilder('c')
            ->select('MIN(c.position)')
            ->andWhere('c.list = :list')
            ->setParameter('list', $boardList)
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
    }

    public function countCardsBetweenPositions(BoardList $boardList, int $prevPosition, int $nextPosition): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.list = :list')
            ->andWhere('c.position > :prevPos')
            ->andWhere('c.position < :nextPos')
            ->setParameter('list', $boardList)
            ->setParameter('prevPos', $prevPosition)
            ->setParameter('nextPos', $nextPosition)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findCardByLastPositionInList(BoardList $boardList): ?Card
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.list = :list')
            ->orderBy('c.position', 'DESC')
            ->setMaxResults(1)
            ->setParameter('list', $boardList)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
