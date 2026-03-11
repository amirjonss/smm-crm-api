<?php

declare(strict_types=1);

namespace App\EventListener\Card;

use App\Component\Board\MercurePublisher;
use App\Component\CardLog\CardLogFactory;
use App\Component\CardLog\CardLogManager;
use App\Event\Card\CardChangedDeadlineEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class CardChangedDeadlineListener
{
    public function __construct(
        private CardLogFactory $cardLogFactory,
        private CardLogManager $cardLogManager,
        private MercurePublisher $mercurePublisher,
    ) {
    }

    public function __invoke(CardChangedDeadlineEvent $event): void
    {
        $user = $event->getUser();
        $userName = $user->getGivenName() . ' ' . $user->getFamilyName();
        $oldDate = $event->getOldCard()->getDeadline()->format('d.m.Y');
        $newDate = $event->getNewCard()->getDeadline()->format('d.m.Y');
        $description = $userName . ' изменил(а) срок с ' . $oldDate . ' на ' . $newDate;
        $cardLog = $this->cardLogFactory->create($event->getNewCard(), $description);
        $cardLog->setCreatedBy($user);

        $this->cardLogManager->save($cardLog, true);
        $this->mercurePublisher->publishCardUpdated($event->getNewCard());
    }
}
