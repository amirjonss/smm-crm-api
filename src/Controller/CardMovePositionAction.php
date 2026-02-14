<?php

declare(strict_types=1);

namespace App\Controller;

use App\Component\Card\Dtos\CardMovePositionDto;
use App\Controller\Base\AbstractController;
use App\Entity\Card;
use App\Service\CardSetPositionService;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CardMovePositionAction extends AbstractController
{
    public function __invoke(CardMovePositionDto $cardMovePositionDto, CardSetPositionService $service): Card
    {
        try {
            $service->setPositionCard(
                $cardMovePositionDto->getCard(),
                $cardMovePositionDto->getPrevCard(),
                $cardMovePositionDto->getNextCard()
            );
        } catch (\Throwable $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $cardMovePositionDto->getCard();
    }
}
