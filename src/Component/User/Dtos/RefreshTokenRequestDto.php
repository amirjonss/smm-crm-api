<?php

declare(strict_types=1);

namespace App\Component\User\Dtos;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RefreshTokenDto.
 */
class RefreshTokenRequestDto
{
    public function __construct(
        #[Groups(['user:write'])]
        #[Assert\NotBlank]
        private ?string $refreshToken = null,
    ) {
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }
}
