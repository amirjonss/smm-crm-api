<?php

declare(strict_types=1);

namespace App\Event\Card;

use App\Entity\BoardList;
use App\Entity\Card;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class CardMovedEvent extends Event
{
    public function __construct(
        private Card $card,
        private User $user,
        private BoardList $oldBoardList,
        private BoardList $newBoardList
    ) {
    }

    public function getNewBoardList(): BoardList
    {
        return $this->newBoardList;
    }

    public function getOldBoardList(): BoardList
    {
        return $this->oldBoardList;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getCard(): Card
    {
        return $this->card;
    }
}
