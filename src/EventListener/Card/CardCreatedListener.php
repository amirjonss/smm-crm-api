<?php

declare(strict_types=1);

namespace App\EventListener\Card;

use App\Component\Board\MercurePublisher;
use App\Component\CardLog\CardLogFactory;
use App\Component\CardLog\CardLogManager;
use App\Event\Card\CardCreatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class CardCreatedListener
{
    public function __construct(
        private CardLogFactory $cardLogFactory,
        private CardLogManager $cardLogManager,
        private MercurePublisher $mercurePublisher,
    ) {
    }

    public function __invoke(CardCreatedEvent $event): void
    {
        $user = $event->getUser();
        $userName = $user->getGivenName() . ' ' . $user->getFamilyName();
        $listName = $event->getCard()->getList()->getName();
        $description = $userName . ' добавил(а) эту карточку в список ' . $listName;
        $cardLog = $this->cardLogFactory->create($event->getCard(), $description);

        $this->cardLogManager->save($cardLog, true);
        $this->mercurePublisher->publishCardCreated($event->getCard());
    }
}
