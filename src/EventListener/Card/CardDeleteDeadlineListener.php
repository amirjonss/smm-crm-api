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
        $userName = $event->getUser()->getGivenName() . ' ' . $event->getUser()->getFamilyName();
        $description = 'Пользователь ' . $userName . ' удалил дедлайн с карточки';
        $cardLog = $this->cardLogFactory->create($event->getCard(), $description);
        $cardLog->setCreatedBy($event->getUser());

        $this->cardLogManager->save($cardLog, true);
    }
}
