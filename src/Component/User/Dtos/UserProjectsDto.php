<?php

declare(strict_types=1);

namespace App\Component\User\Dtos;

use Symfony\Component\Serializer\Annotation\Groups;

readonly class UserProjectsDto
{
    public function __construct(
        #[Groups(['user:projects:read'])]
        private ?string $givenName,
        #[Groups(['user:projects:read'])]
        private ?string $familyName,
        #[Groups(['user:projects:read'])]
        private array $projects
    ) {
    }

    public function getGivenName(): ?string
    {
        return $this->givenName;
    }

    public function getFamilyName(): ?string
    {
        return $this->familyName;
    }

    public function getProjects(): array
    {
        return $this->projects;
    }
}
