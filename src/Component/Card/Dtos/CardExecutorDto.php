<?php

declare(strict_types=1);

namespace App\Component\Card\Dtos;

use App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class CardExecutorDto
{
    public function __construct(
        #[Groups(['card:executor:write'])]
        #[Assert\NotBlank]
        private User $executor
    ) {
    }

    public function getExecutor(): User
    {
        return $this->executor;
    }
}
