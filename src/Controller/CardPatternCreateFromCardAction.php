<?php

declare(strict_types=1);

namespace App\Controller;

use App\Component\CardPattern\Dtos\CardPatternCreateFromCardDto;
use App\Controller\Base\AbstractController;
use App\Entity\CardPattern;
use App\Service\CardPatternService;

class CardPatternCreateFromCardAction extends AbstractController
{
    public function __invoke(CardPatternCreateFromCardDto $dto, CardPatternService $service): CardPattern
    {
        return $service->createFromCard($dto->getCard(), $this->getUser());
    }
}
