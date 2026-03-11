<?php

declare(strict_types=1);

namespace App\Service;

use App\Component\User\CurrentUser;
use App\Entity\BoardList;
use App\Event\Card\CardArchivedEvent;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BoardListArchiveAllCardsService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EventDispatcherInterface $eventDispatcher,
        private CurrentUser $currentUser,
        private CardRepository $cardRepository,
    ) {
    }

    public function archiveAllCardsInBoardList(BoardList $boardList): void
    {
        $user = $this->currentUser->getUser();
        $unarchivedBoardListCards = $this->cardRepository->findBy(['list' => $boardList, 'isArchived' => false]);

        foreach ($unarchivedBoardListCards as $card) {
            if ($card->isArchived()) {
                continue;
            }

            $card->setIsArchived(true);
            $card->setPosition(null);
            $card->setUpdatedAt(new \DateTime());

            $this->eventDispatcher->dispatch(new CardArchivedEvent($card, $user));
        }

        $this->entityManager->flush();
    }
}
