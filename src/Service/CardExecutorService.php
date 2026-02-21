<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Card;
use App\Entity\User;
use App\Event\Card\CardAddedExecutorEvent;
use App\Event\Card\CardRemovedExecutorEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CardExecutorService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function remove(Card $card, User $executor, User $currentUser): void
    {
        if (!$card->getExecutor()->contains($executor)) {
            throw new UnprocessableEntityHttpException('This user is not an executor of this card.');
        }

        $card->removeExecutor($executor);
        $this->entityManager->flush();
        $this->eventDispatcher->dispatch(new CardRemovedExecutorEvent($card, $currentUser, $executor));
    }

    public function add(Card $card, User $executor, User $currentUser): void
    {
        if ($card->getExecutor()->contains($executor)) {
            throw new UnprocessableEntityHttpException('This user is already an executor of this card.');
        }

        $card->addExecutor($executor);
        $this->eventDispatcher->dispatch(new CardAddedExecutorEvent($card, $currentUser, $executor));
    }
}
