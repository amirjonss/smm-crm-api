<?php

namespace App\Component\CardLog;

use App\Entity\Card;
use App\Entity\CardLog;

class CardLogFactory
{
    public function create(Card $card, string $description): CardLog
    {
        $cardLog = new CardLog();
        $cardLog
            ->setCard($card)
            ->setDescription($description)
            ->setCreatedBy($card->getCreatedBy())
            ->setCreatedAt(new \DateTime());

        return $cardLog;
    }
}
