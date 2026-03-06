<?php

declare(strict_types=1);

namespace App\EventListener\Card;

use App\Component\Board\MercurePublisher;
use App\Component\CardLog\CardLogFactory;
use App\Component\CardLog\CardLogManager;
use App\Event\Card\CardAddedExecutorEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class CardAddedExecutorListener
{
    public function __construct(
        private CardLogFactory $cardLogFactory,
        private CardLogManager $cardLogManager,
        private MercurePublisher $mercurePublisher,
    ) {
    }

    public function __invoke(CardAddedExecutorEvent $event): void
    {
        $user = $event->getUser();
        $userName = $user->getGivenName() . ' ' . $user->getFamilyName();
        $executorName = $event->getExecutor()->getGivenName() . ' ' . $event->getExecutor()->getFamilyName();
        $description = $userName . ' добавил(а) участника ' . $executorName . ' на эту карточку';
        $cardLog = $this->cardLogFactory->create($event->getCard(), $description);
        $cardLog->setCreatedBy($user);

        $this->cardLogManager->save($cardLog, true);
        $this->mercurePublisher->publishCardUpdated($event->getCard());
    }
}
