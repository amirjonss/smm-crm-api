<?php

declare(strict_types=1);

namespace App\Component\User\Dtos;

use Symfony\Component\Serializer\Annotation\Groups;

readonly class UserProjectsDetail
{
    public function __construct(
        #[Groups(['user:projects:read'])]
        private ?int $id = null,

        #[Groups(['user:projects:read'])]
        private ?string $name = null,

        #[Groups(['user:projects:read'])]
        private bool $isActive = false,

        #[Groups(['user:projects:read'])]
        private ?int $chargeDay = null,

        #[Groups(['user:projects:read'])]
        private int $price = 0,

        #[Groups(['user:projects:read'])]
        private int $graphicPostCount = 0,

        #[Groups(['user:projects:read'])]
        private int $videoPostCount = 0,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getChargeDay(): ?int
    {
        return $this->chargeDay;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getGraphicPostCount(): int
    {
        return $this->graphicPostCount;
    }

    public function getVideoPostCount(): int
    {
        return $this->videoPostCount;
    }
}
