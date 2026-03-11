<?php

declare(strict_types=1);

namespace App\EventListener\Card;

use App\Component\Board\MercurePublisher;
use App\Component\CardLog\CardLogFactory;
use App\Component\CardLog\CardLogManager;
use App\Event\Card\CardMovedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class CardMovedListener
{
    public function __construct(
        private CardLogFactory $cardLogFactory,
        private CardLogManager $cardLogManager,
        private MercurePublisher $mercurePublisher,
    ) {
    }

    public function __invoke(CardMovedEvent $event): void
    {
        $user = $event->getUser();
        $userName = $user->getGivenName() . ' ' . $user->getFamilyName();
        $description = $userName . ' переместил(а) эту карточку из списка «' . $event->getOldBoardList()->getName(
        ) . '» в список «' . $event->getNewBoardList()->getName() . '»';
        $cardLog = $this->cardLogFactory->create($event->getCard(), $description);
        $cardLog->setCreatedBy($user);

        $this->cardLogManager->save($cardLog, true);
        $this->mercurePublisher->publishCardMoved($event->getCard(), $event->getOldBoardList()->getId());
    }
}
