<?php

declare(strict_types=1);

namespace App\Component\CardPattern\Dtos;

use App\Entity\Card;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class CardPatternCreateFromCardDto
{
    public function __construct(
        #[Groups(['card-pattern:create-from-card:write'])]
        #[Assert\NotNull]
        private Card $card,
    ) {
    }

    public function getCard(): Card
    {
        return $this->card;
    }
}
