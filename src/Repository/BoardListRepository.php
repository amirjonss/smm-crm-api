<?php

namespace App\Repository;

use App\Entity\Board;
use App\Entity\BoardList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BoardList>
 */
class BoardListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BoardList::class);
    }

    public function findBoardListByLastPositionInBoard(Board $board, ?BoardList $exclude = null): ?BoardList
    {
        $qb = $this->createQueryBuilder('bl')
            ->andWhere('bl.board = :board')
            ->orderBy('bl.position', 'DESC')
            ->setMaxResults(1)
            ->setParameter('board', $board);

        if ($exclude !== null && $exclude->getId() !== null) {
            $qb->andWhere('bl.id != :excludeId')
                ->setParameter('excludeId', $exclude->getId());
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function countBoardListsBetweenPositions(Board $board, int $prevPosition, int $nextPosition, ?BoardList $exclude = null): int
    {
        $qb = $this->createQueryBuilder('bl')
            ->select('COUNT(bl.id)')
            ->where('bl.board = :board')
            ->andWhere('bl.position > :prevPos')
            ->andWhere('bl.position < :nextPos')
            ->setParameter('board', $board)
            ->setParameter('prevPos', $prevPosition)
            ->setParameter('nextPos', $nextPosition);

        if ($exclude !== null && $exclude->getId() !== null) {
            $qb->andWhere('bl.id != :excludeId')
                ->setParameter('excludeId', $exclude->getId());
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
