<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Base\AbstractController;
use App\Entity\Card;
use App\Event\Card\CardCreatedEvent;
use App\Repository\CardRepository;
use App\Service\CardSetPositionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class CardCreateAction extends AbstractController
{
    public function __invoke(
        Card $card,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
        CardSetPositionService $setPositionCardService,
        CardRepository $cardRepository,
    ): Card {
        $prevCard = $cardRepository->findCardByLastPositionInList($card->getList(), $card);

        $card->setCreatedAt(new \DateTime());
        $card->setPosition($setPositionCardService->calculatePosition($card, $card->getList(), $prevCard, null));
        $card->setCreatedBy($this->getUser());
        $entityManager->persist($card);
        $entityManager->flush();
        $eventDispatcher->dispatch(new CardCreatedEvent($card, $this->getUser()));

        return $card;
    }
}
