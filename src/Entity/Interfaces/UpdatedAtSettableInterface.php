<?php

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface UpdatedAtSettableInterface
{
    public function setUpdatedAt(\DateTimeInterface $updatedAt);
}
