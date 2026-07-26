<?php

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Validator\ValidatorInterface;
use App\Component\User\CurrentUser;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProjectCreateService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CurrentUser $currentUser,
        private ValidatorInterface $validator,
    ) {
    }

    public function create(Project $project): Project
    {
        $project->setCreatedAt(new \DateTime());
        $project->setCreatedBy($this->currentUser->getUser());

        $this->validator->validate($project);

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $project;
    }
}
