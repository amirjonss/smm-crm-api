<?php

declare(strict_types=1);

namespace App\Validator\Card;

use App\Component\Card\Enum\CardStatus;
use App\Component\User\Enum\Roles;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CardChangeStatusValidator
{
    public function validate(CardStatus $newStatus, User $user): void
    {
        $roles = $user->getRoles();

        if ($newStatus === CardStatus::DONE && !$this->hasAnyRole($roles, [Roles::ADMIN, Roles::SMM])) {
            throw new UnprocessableEntityHttpException('Only admin and smm roles can set card status to done.');
        }

        if (
            in_array($newStatus, [CardStatus::IN_PROGRESS, CardStatus::REVIEW], true)
            && $this->hasAnyRole($roles, [Roles::USER])
        ) {
            throw new UnprocessableEntityHttpException(
                'Role user cannot set card status to in_progress or review.'
            );
        }
    }

    /**
     * @param string[] $userRoles
     * @param Roles[] $allowedRoles
     */
    private function hasAnyRole(array $userRoles, array $allowedRoles): bool
    {
        foreach ($allowedRoles as $allowedRole) {
            if (in_array($allowedRole->value, $userRoles, true)) {
                return true;
            }
        }

        return false;
    }
}
