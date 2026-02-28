<?php

declare(strict_types=1);

namespace App\Controller;

use App\Component\Card\Dtos\CardMovePositionDto;
use App\Component\User\CurrentUser;
use App\Controller\Base\AbstractController;
use App\Event\Card\CardMovedEvent;
use App\Service\CardSetPositionService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class CardMovePositionAction extends AbstractController
{
    public function __invoke(
        CardMovePositionDto $cardMovePositionDto,
        CardSetPositionService $service,
        EventDispatcherInterface $eventDispatcher,
        CurrentUser $currentUser,
    ): Response {
        $card = $cardMovePositionDto->getCard();
        $oldList = $card->getList();

        try {
            $service->setPositionCard(
                $card,
                $cardMovePositionDto->getPrevCard(),
                $cardMovePositionDto->getNextCard(),
                $cardMovePositionDto->getTargetList(),
            );
        } catch (\Throwable $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        if ($oldList !== null && $oldList->getId() !== $card->getList()?->getId()) {
            $eventDispatcher->dispatch(
                new CardMovedEvent($card, $currentUser->getUser(), $oldList, $card->getList())
            );
        }

        return $this->responseNormalized($card);
    }
}
