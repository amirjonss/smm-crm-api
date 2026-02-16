<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Card;
use App\Entity\CardPattern;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CardPatternService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function createFromCard(Card $card, User $createdBy): CardPattern
    {
        $cardPattern = (new CardPattern())
            ->setCreatedBy($createdBy)
            ->setName($card->getName() ?? '')
            ->setDeadline($card->getDeadline())
            ->setDescription($card->getDescription());

        $this->entityManager->persist($cardPattern);
        $this->entityManager->flush();

        return $cardPattern;
    }
}
