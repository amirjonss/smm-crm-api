<?php

declare(strict_types=1);

namespace App\EventListener\Card;

use App\Component\CardLog\CardLogFactory;
use App\Component\CardLog\CardLogManager;
use App\Event\Card\CardAddedExecutorEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class CardAddedExecutorListener
{
    public function __construct(private CardLogFactory $cardLogFactory, private CardLogManager $cardLogManager)
    {
    }

    public function __invoke(CardAddedExecutorEvent $event): void
    {
        $userName = $event->getUser()->getGivenName() . ' ' . $event->getUser()->getFamilyName();
        $executorName = $event->getExecutor()->getGivenName() . ' ' . $event->getExecutor()->getFamilyName();
        $description = 'Пользователь ' . $userName . ' добавил исполнителя ' . $executorName . ' на карточку';
        $cardLog = $this->cardLogFactory->create($event->getCard(), $description);
        $cardLog->setCreatedBy($event->getUser());

        $this->cardLogManager->save($cardLog, true);
    }
}
