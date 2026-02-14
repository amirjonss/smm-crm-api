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

    public function setPositionCard(Card $card, ?Card $prevCard, ?Card $nextCard): void
    {
        $this->validateNeighbors($card, $prevCard, $nextCard);

        $this->entityManager->beginTransaction();

        try {
            $newPosition = $this->calculatePosition($card, $prevCard, $nextCard);

            $card->setPosition($newPosition);
            $this->entityManager->persist($card);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    public function calculatePosition(Card $card, ?Card $prevCard, ?Card $nextCard): int
    {
        if ($prevCard === null && $nextCard === null) {
            return 0;
        }

        if ($nextCard === null) {
            return $prevCard->getPosition() + self::POSITION_GAP;
        }

        if ($prevCard === null) {
            $nextPos = $nextCard->getPosition();

            if ($nextPos <= 1) {
                $this->reindexCardsPosition($card->getList());
                $nextPos = $this->cardRepository->findMinCardPositionNumberInList($card->getList());
            }

            return intdiv($nextPos, 2);
        }

        $prevPos = $prevCard->getPosition();
        $nextPos = $nextCard->getPosition();

        if (($nextPos - $prevPos) <= 1) {
            $this->reindexCardsPosition($card->getList());
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

    private function validateNeighbors(Card $card, ?Card $prevCard, ?Card $nextCard): void
    {
        $boardList = $card->getList();

        if ($prevCard !== null && $prevCard->getList()?->getId() !== $boardList?->getId()) {
            throw new \InvalidArgumentException('prevCard does not belong to the same list.');
        }

        if ($nextCard !== null && $nextCard->getList()?->getId() !== $boardList?->getId()) {
            throw new \InvalidArgumentException('nextCard does not belong to the same list.');
        }

        if ($prevCard !== null && $nextCard !== null && $prevCard->getPosition() >= $nextCard->getPosition()) {
            throw new \InvalidArgumentException('prevCard must have a lower position than nextCard.');
        }

        if ($prevCard !== null && $nextCard !== null) {
            $countBetween = $this->cardRepository->countCardsBetweenPositions(
                $boardList,
                $prevCard->getPosition(),
                $nextCard->getPosition(),
            );

            if ($countBetween > 0) {
                throw new \InvalidArgumentException('prevCard and nextCard are not adjacent — there are cards between them.');
            }
        }
    }
}
