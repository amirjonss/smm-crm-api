<?php

declare(strict_types=1);

namespace App\EventListener\Card;

use App\Component\Board\MercurePublisher;
use App\Component\CardLog\CardLogFactory;
use App\Component\CardLog\CardLogManager;
use App\Event\Card\CardSetDeadlineEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class CardSetDeadlineListener
{
    public function __construct(
        private CardLogFactory $cardLogFactory,
        private CardLogManager $cardLogManager,
        private MercurePublisher $mercurePublisher,
    ) {
    }

    public function __invoke(CardSetDeadlineEvent $event): void
    {
        $user = $event->getUser();
        $userName = $user->getGivenName() . ' ' . $user->getFamilyName();
        $deadline = $event->getCard()->getDeadline()->format('d.m.Y');
        $description = $userName . ' установил(а) срок ' . $deadline . ' на эту карточку';
        $cardLog = $this->cardLogFactory->create($event->getCard(), $description);
        $cardLog->setCreatedBy($user);

        $this->cardLogManager->save($cardLog, true);
        $this->mercurePublisher->publishCardUpdated($event->getCard());
    }
}
