<?php

namespace App\Service;

use App\Entity\BoardList;
use App\Entity\Card;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;

class CardSetPositionService
{
    private const int POSITION_GAP = 100;

    public function __construct(
        private CardRepository $cardRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function setPositionCard(Card $card, ?Card $prevCard, ?Card $nextCard, ?BoardList $targetList = null): void
    {
        $targetList = $this->resolveTargetList($card, $prevCard, $nextCard, $targetList);
        $this->validateNeighbors($card, $targetList, $prevCard, $nextCard);

        $this->entityManager->beginTransaction();

        try {
            $newPosition = $this->calculatePosition($card, $targetList, $prevCard, $nextCard);

            $card->setList($targetList);
            $card->setPosition($newPosition);
            $this->entityManager->persist($card);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    private function resolveTargetList(Card $card, ?Card $prevCard, ?Card $nextCard, ?BoardList $targetList): BoardList
    {
        if ($prevCard !== null) {
            $resolved = $prevCard->getList();
        } elseif ($nextCard !== null) {
            $resolved = $nextCard->getList();
        } elseif ($targetList !== null) {
            $resolved = $targetList;
        } else {
            $resolved = $card->getList();
        }

        if ($resolved === null) {
            throw new \InvalidArgumentException('Cannot determine target list.');
        }

        if ($targetList !== null && $resolved->getId() !== $targetList->getId()) {
            throw new \InvalidArgumentException('targetList conflicts with the list of prevCard/nextCard.');
        }

        return $resolved;
    }

    private function validateNeighbors(Card $card, BoardList $targetList, ?Card $prevCard, ?Card $nextCard): void
    {
        if ($prevCard !== null && $prevCard->getList()?->getId() !== $targetList->getId()) {
            throw new \InvalidArgumentException('prevCard does not belong to the target list.');
        }

        if ($nextCard !== null && $nextCard->getList()?->getId() !== $targetList->getId()) {
            throw new \InvalidArgumentException('nextCard does not belong to the target list.');
        }

        if ($prevCard !== null && $nextCard !== null && $prevCard->getPosition() >= $nextCard->getPosition()) {
            throw new \InvalidArgumentException('prevCard must have a lower position than nextCard.');
        }

        if ($prevCard !== null && $nextCard !== null) {
            $countBetween = $this->cardRepository->countCardsBetweenPositions(
                $targetList,
                $prevCard->getPosition(),
                $nextCard->getPosition(),
                $card,
            );

            if ($countBetween > 0) {
                throw new \InvalidArgumentException(
                    'prevCard and nextCard are not adjacent — there are cards between them.'
                );
            }
        }
    }

    public function calculatePosition(Card $card, BoardList $targetList, ?Card $prevCard, ?Card $nextCard): int
    {
        if ($prevCard === null && $nextCard === null) {
            $lastCard = $this->cardRepository->findCardByLastPositionInList($targetList, $card);

            return $lastCard !== null ? $lastCard->getPosition() + self::POSITION_GAP : self::POSITION_GAP;
        }

        if ($nextCard === null) {
            return $prevCard->getPosition() + self::POSITION_GAP;
        }

        if ($prevCard === null) {
            $nextPos = $nextCard->getPosition();

            if ($nextPos <= 1) {
                $this->reindexCardsPosition($targetList);
                $this->entityManager->refresh($nextCard);
                $nextPos = $nextCard->getPosition();
            }

            return intdiv($nextPos, 2);
        }

        $prevPos = $prevCard->getPosition();
        $nextPos = $nextCard->getPosition();

        if (($nextPos - $prevPos) <= 1) {
            $this->reindexCardsPosition($targetList);
            $this->entityManager->refresh($prevCard);
            $this->entityManager->refresh($nextCard);
            $prevPos = $prevCard->getPosition();
            $nextPos = $nextCard->getPosition();
        }

        return intdiv($prevPos + $nextPos, 2);
    }

    private function reindexCardsPosition(BoardList $boardList): void
    {
        $cards = $this->cardRepository->findBy(['list' => $boardList], ['position' => 'ASC']);

        $position = 0;

        foreach ($cards as $card) {
            $position += self::POSITION_GAP;
            $card->setPosition($position);
        }

        $this->entityManager->flush();
    }
}
