<?php

declare(strict_types=1);

namespace App\Event\Card;

use App\Entity\Card;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class CardRenamedEvent extends Event
{
    public function __construct(
        private Card $card,
        private User $user,
        private string $oldName,
        private string $newName
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getCard(): Card
    {
        return $this->card;
    }

    public function getOldName(): string
    {
        return $this->oldName;
    }

    public function getNewName(): string
    {
        return $this->newName;
    }
}
