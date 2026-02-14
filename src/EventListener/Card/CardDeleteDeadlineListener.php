<?php

declare(strict_types=1);

namespace App\EventListener\Card;

use App\Component\CardLog\CardLogFactory;
use App\Component\CardLog\CardLogManager;
use App\Event\Card\CardDeleteDeadlineEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class CardDeleteDeadlineListener
{
    public function __construct(private CardLogFactory $cardLogFactory, private CardLogManager $cardLogManager)
    {
    }

    public function __invoke(CardDeleteDeadlineEvent $event): void
    {
        $user = $event->getUser();
        $userName = $user->getGivenName() . ' ' . $user->getFamilyName();
        $description = $userName . ' удалил(а) срок с этой карточки';
        $cardLog = $this->cardLogFactory->create($event->getCard(), $description);
        $cardLog->setCreatedBy($user);

        $this->cardLogManager->save($cardLog, true);
    }
}
