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

    public function countCardsBetweenPositions(BoardList $boardList, int $prevPosition, int $nextPosition, ?Card $excludeCard = null): int
    {
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.list = :list')
            ->andWhere('c.position > :prevPos')
            ->andWhere('c.position < :nextPos')
            ->setParameter('list', $boardList)
            ->setParameter('prevPos', $prevPosition)
            ->setParameter('nextPos', $nextPosition);

        if ($excludeCard !== null && $excludeCard->getId() !== null) {
            $qb->andWhere('c.id != :excludeId')
                ->setParameter('excludeId', $excludeCard->getId());
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function findCardByLastPositionInList(BoardList $boardList, ?Card $excludeCard = null): ?Card
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.list = :list')
            ->orderBy('c.position', 'DESC')
            ->setMaxResults(1)
            ->setParameter('list', $boardList);

        if ($excludeCard !== null && $excludeCard->getId() !== null) {
            $qb->andWhere('c.id != :excludeId')
                ->setParameter('excludeId', $excludeCard->getId());
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
