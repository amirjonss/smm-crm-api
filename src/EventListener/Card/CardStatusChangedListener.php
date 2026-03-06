<?php

declare(strict_types=1);

namespace App\EventListener\Card;

use App\Component\Board\MercurePublisher;
use App\Component\Card\Enum\CardStatus;
use App\Component\CardLog\CardLogFactory;
use App\Component\CardLog\CardLogManager;
use App\Event\Card\CardStatusChangedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class CardStatusChangedListener
{
    public function __construct(
        private CardLogFactory $cardLogFactory,
        private CardLogManager $cardLogManager,
        private MercurePublisher $mercurePublisher,
    ) {
    }

    public function __invoke(CardStatusChangedEvent $event): void
    {
        $user = $event->getUser();
        $userName = $user->getGivenName() . ' ' . $user->getFamilyName();
        $statusRu = CardStatus::getStatusRuByStatus($event->getCard()->getStatus());
        $description = $userName . ' изменил(а) статус на «' . $statusRu . '»';
        $cardLog = $this->cardLogFactory->create($event->getCard(), $description);
        $cardLog->setCreatedBy($user);

        $this->cardLogManager->save($cardLog, true);
        $this->mercurePublisher->publishCardUpdated($event->getCard());
    }
}
