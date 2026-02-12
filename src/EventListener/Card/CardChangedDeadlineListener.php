<?php

declare(strict_types=1);

namespace App\EventListener\Card;

use App\Component\CardLog\CardLogFactory;
use App\Component\CardLog\CardLogManager;
use App\Event\Card\CardChangedDeadlineEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class CardChangedDeadlineListener
{
    public function __construct(private CardLogFactory $cardLogFactory, private CardLogManager $cardLogManager)
    {
    }

    public function __invoke(CardChangedDeadlineEvent $event): void
    {
        $userName = $event->getUser()->getGivenName() . ' ' . $event->getUser()->getFamilyName();
        $description = 'Пользователь ' . $userName . ' изменил дедлайн с ' . $event->getOldCard()->getDeadline(
            )->format(
                'd.m.Y'
            ) . ' на ' . $event->getNewCard()->getDeadline()->format('d.m.Y');
        $cardLog = $this->cardLogFactory->create($event->getNewCard(), $description);
        $cardLog->setCreatedBy($event->getUser());

        $this->cardLogManager->save($cardLog, true);
    }
}
