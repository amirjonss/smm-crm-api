<?php

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface DeletedAtSettableInterface
{
    public function setDeletedAt(\DateTimeInterface $deletedAt): self;
}
