<?php

declare(strict_types=1);

namespace App\Event\Card;

use App\Entity\Card;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class CardUnarchivedEvent extends Event
{
    public function __construct(private Card $card, private User $user)
    {
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
