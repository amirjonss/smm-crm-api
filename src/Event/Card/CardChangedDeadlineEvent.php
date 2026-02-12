<?php

declare(strict_types=1);

namespace App\Event\Card;

use App\Entity\Card;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class CardChangedDeadlineEvent extends Event
{
    public function __construct(private Card $oldCard, private Card $newCard, private User $user)
    {
    }

    public function getOldCard(): Card
    {
        return $this->oldCard;
    }

    public function getNewCard(): Card
    {
        return $this->newCard;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
