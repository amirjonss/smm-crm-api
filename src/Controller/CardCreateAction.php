<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Base\AbstractController;
use App\Entity\Card;
use App\Service\SetPositionCardService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class CardCreateAction extends AbstractController
{
    public function __invoke(
        Card $card,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
        SetPositionCardService $setPositionCardService
    ): Card {
        $card->setCreatedAt(new \DateTime());
        $card->setPosition($setPositionCardService->getPositionCard());
        $card->setCreatedBy($this->getUser());
        $entityManager->persist($card);
        $entityManager->flush();

        return $card;
    }
}
