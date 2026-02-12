<?php

namespace App\Service;

use App\Repository\CardRepository;

class SetPositionCardService
{
    public function __construct(private CardRepository $cardRepository)
    {
    }

    public function getPositionCard(): int
    {
        return $this->cardRepository->findMaxCardPositionNumber() + 1;
    }
}
