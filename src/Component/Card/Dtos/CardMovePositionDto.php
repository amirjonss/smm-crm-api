<?php

declare(strict_types=1);

namespace App\Component\Card\Dtos;

use App\Entity\Card;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class CardMovePositionDto
{
    public function __construct(
        #[Groups(['card:move-position:write'])]
        private Card $card,
        #[Groups(['card:move-position:write'])]
        private ?Card $prevCard,
        #[Groups(['card:move-position:write'])]
        private ?Card $nextCard
    ) {
    }

    public function getCard(): Card
    {
        return $this->card;
    }

    public function getPrevCard(): ?Card
    {
        return $this->prevCard;
    }

    public function getNextCard(): ?Card
    {
        return $this->nextCard;
    }
}
