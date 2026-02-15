<?php

declare(strict_types=1);

namespace App\Controller\Subscribers;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Component\User\CurrentUser;
use App\Entity\Card;
use App\Event\Card\CardArchivedEvent;
use App\Event\Card\CardChangedDeadlineEvent;
use App\Event\Card\CardDeleteDeadlineEvent;
use App\Event\Card\CardRenamedEvent;
use App\Event\Card\CardSetDeadlineEvent;
use App\Event\Card\CardStatusChangedEvent;
use App\Event\Card\CardUnarchivedEvent;
use App\Repository\CardRepository;
use App\Service\CardSetPositionService;
use App\Validator\Card\CardChangeStatusValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CardChangeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private CurrentUser $currentUser,
        private EventDispatcherInterface $eventDispatcher,
        private CardChangeStatusValidator $cardStatusChangeValidationService,
        private CardSetPositionService $cardSetPositionService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onWrite', EventPriorities::POST_WRITE],
        ];
    }

    public function onWrite(ViewEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->getMethod() !== Request::METHOD_PATCH) {
            return;
        }

        $card = $event->getControllerResult();

        if (!$card instanceof Card) {
            return;
        }

        $previousCard = $request->attributes->get('previous_data');

        if (!$previousCard instanceof Card) {
            return;
        }

        $this->renameAction($previousCard, $card);
        $this->setDeadlineAction($previousCard, $card);
        $this->changeDeadlineAction($previousCard, $card);
        $this->deleteDeadlineAction($previousCard, $card);
        $this->archiveAction($previousCard, $card);
        $this->unarchiveAction($previousCard, $card);
        $this->changeStatusAction($previousCard, $card);
    }

    public function renameAction(Card $previousCard, Card $card): void
    {
        $oldName = $previousCard->getName();
        $newName = $card->getName();

        if ($oldName === null || $newName === null || $oldName === $newName) {
            return;
        }
        $this->eventDispatcher->dispatch(
            new CardRenamedEvent($card, $this->currentUser->getUser(), $oldName, $newName)
        );
    }

    public function setDeadlineAction(Card $oldCard, Card $newCard): void
    {
        if ($oldCard->getDeadline() === null && $newCard->getDeadline() !== null) {
            $this->eventDispatcher->dispatch(
                new CardSetDeadlineEvent($newCard, $this->currentUser->getUser())
            );
        }
    }

    public function changeDeadlineAction(Card $oldCard, Card $newCard): void
    {
        if ($oldCard->getDeadline() !== null && $newCard->getDeadline() !== null && $oldCard->getDeadline()->format(
                'd.m.Y'
            ) !== $newCard->getDeadline()->format('d.m.Y')) {
            $this->eventDispatcher->dispatch(
                new CardChangedDeadlineEvent($oldCard, $newCard, $this->currentUser->getUser())
            );
        }
    }

    public function deleteDeadlineAction(Card $oldCard, Card $newCard): void
    {
        if ($oldCard->getDeadline() !== null && $newCard->getDeadline() === null) {
            $this->eventDispatcher->dispatch(
                new CardDeleteDeadlineEvent($newCard, $this->currentUser->getUser())
            );
        }
    }

    public function archiveAction(Card $oldCard, Card $newCard): void
    {
        if ($oldCard->isArchived() === false && $newCard->isArchived() === true) {
            $this->eventDispatcher->dispatch(
                new CardArchivedEvent($newCard, $this->currentUser->getUser())
            );

            $newCard->setPosition(null);
            $this->entityManager->flush();
        }
    }

    public function unarchiveAction(Card $oldCard, Card $newCard): void
    {
        if ($oldCard->isArchived() === true && $newCard->isArchived() === false) {
            $this->eventDispatcher->dispatch(
                new CardUnarchivedEvent($newCard, $this->currentUser->getUser())
            );

            $position = $this->cardSetPositionService->calculatePosition(
                $newCard,
                $newCard->getList(),
                null,
                null,
            );
            $newCard->setPosition($position);
            $this->entityManager->flush();
        }
    }

    public function changeStatusAction(Card $oldCard, Card $newCard): void
    {
        if ($oldCard->getStatus() !== $newCard->getStatus()) {
            $this->cardStatusChangeValidationService->validate($newCard->getStatus(), $this->currentUser->getUser());
            $this->eventDispatcher->dispatch(
                new CardStatusChangedEvent($newCard, $this->currentUser->getUser())
            );
        }
    }
}
