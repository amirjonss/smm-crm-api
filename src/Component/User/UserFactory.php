<?php

declare(strict_types=1);

namespace App\Component\User;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(private UserPasswordHasherInterface $passwordEncoder)
    {
    }

    public function create(string $email, string $password, string $givenName, ?string $familyName = null, array $roles = []): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setCreatedAt(new \DateTime());
        $user->setPassword($this->passwordEncoder->hashPassword($user, $password));
        $user->setGivenName($givenName);

        if ($familyName !== null) {
            $user->setFamilyName($familyName);
        }

        if (!empty($roles)) {
            $user->setRoles($roles);
        }

        return $user;
    }
}
