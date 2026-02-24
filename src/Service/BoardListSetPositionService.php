<?php

namespace App\Service;

use App\Entity\Board;
use App\Entity\BoardList;
use App\Repository\BoardListRepository;
use Doctrine\ORM\EntityManagerInterface;

class BoardListSetPositionService
{
    private const int POSITION_GAP = 100;

    public function __construct(
        private BoardListRepository $boardListRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function setPositionBoardList(BoardList $boardList, ?BoardList $prevList, ?BoardList $nextList, ?Board $targetBoard = null): void
    {
        $targetBoard = $this->resolveTargetBoard($boardList, $prevList, $nextList, $targetBoard);
        $this->validateNeighbors($boardList, $targetBoard, $prevList, $nextList);

        $this->entityManager->beginTransaction();

        try {
            $newPosition = $this->calculatePosition($boardList, $targetBoard, $prevList, $nextList);

            $boardList->setBoard($targetBoard);
            $boardList->setPosition($newPosition);
            $this->entityManager->persist($boardList);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    private function resolveTargetBoard(BoardList $boardList, ?BoardList $prevList, ?BoardList $nextList, ?Board $targetBoard): Board
    {
        if ($prevList !== null) {
            $resolved = $prevList->getBoard();
        } elseif ($nextList !== null) {
            $resolved = $nextList->getBoard();
        } elseif ($targetBoard !== null) {
            $resolved = $targetBoard;
        } else {
            $resolved = $boardList->getBoard();
        }

        if ($resolved === null) {
            throw new \InvalidArgumentException('Cannot determine target board.');
        }

        if ($targetBoard !== null && $resolved->getId() !== $targetBoard->getId()) {
            throw new \InvalidArgumentException('targetBoard conflicts with the board of prevBoardList/nextBoardList.');
        }

        return $resolved;
    }

    private function validateNeighbors(BoardList $boardList, Board $targetBoard, ?BoardList $prevList, ?BoardList $nextList): void
    {
        if ($prevList !== null && $prevList->getBoard()?->getId() !== $targetBoard->getId()) {
            throw new \InvalidArgumentException('prevBoardList does not belong to the target board.');
        }

        if ($nextList !== null && $nextList->getBoard()?->getId() !== $targetBoard->getId()) {
            throw new \InvalidArgumentException('nextBoardList does not belong to the target board.');
        }

        if ($prevList !== null && $nextList !== null && $prevList->getPosition() >= $nextList->getPosition()) {
            throw new \InvalidArgumentException('prevBoardList must have a lower position than nextBoardList.');
        }

        if ($prevList !== null && $nextList !== null) {
            $countBetween = $this->boardListRepository->countBoardListsBetweenPositions(
                $targetBoard,
                $prevList->getPosition(),
                $nextList->getPosition(),
                $boardList,
            );

            if ($countBetween > 0) {
                throw new \InvalidArgumentException(
                    'prevBoardList and nextBoardList are not adjacent — there are board lists between them.'
                );
            }
        }
    }

    public function calculatePosition(BoardList $boardList, Board $board, ?BoardList $prevList, ?BoardList $nextList): int
    {
        if ($prevList === null && $nextList === null) {
            $lastList = $this->boardListRepository->findBoardListByLastPositionInBoard($board, $boardList);

            return $lastList !== null ? $lastList->getPosition() + self::POSITION_GAP : self::POSITION_GAP;
        }

        if ($nextList === null) {
            return $prevList->getPosition() + self::POSITION_GAP;
        }

        if ($prevList === null) {
            $nextPos = $nextList->getPosition();

            if ($nextPos <= 1) {
                $this->reindexBoardListsPosition($board);
                $this->entityManager->refresh($nextList);
                $nextPos = $nextList->getPosition();
            }

            return intdiv($nextPos, 2);
        }

        $prevPos = $prevList->getPosition();
        $nextPos = $nextList->getPosition();

        if (($nextPos - $prevPos) <= 1) {
            $this->reindexBoardListsPosition($board);
            $this->entityManager->refresh($prevList);
            $this->entityManager->refresh($nextList);
            $prevPos = $prevList->getPosition();
            $nextPos = $nextList->getPosition();
        }

        return intdiv($prevPos + $nextPos, 2);
    }

    private function reindexBoardListsPosition(Board $board): void
    {
        $lists = $this->boardListRepository->findBy(['board' => $board], ['position' => 'ASC']);

        $position = 0;

        foreach ($lists as $list) {
            $position += self::POSITION_GAP;
            $list->setPosition($position);
        }

        $this->entityManager->flush();
    }
}
