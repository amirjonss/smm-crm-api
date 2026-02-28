<?php

declare(strict_types=1);

namespace App\Controller;

use App\Component\Card\Dtos\CardExecutorDto;
use App\Controller\Base\AbstractController;
use App\Entity\Card;
use App\Service\CardExecutorService;
use Symfony\Component\HttpFoundation\Response;

class CardAddExecutorAction extends AbstractController
{
    public function __invoke(Card $card, CardExecutorDto $dto, CardExecutorService $service): Response
    {
        $service->add($card, $dto->getExecutor(), $this->getUser());

        return $this->responseNormalized($card);
    }
}
