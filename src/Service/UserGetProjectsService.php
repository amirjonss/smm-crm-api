<?php

namespace App\Service;

use App\Component\User\Dtos\UserProjectsDetailDto;
use App\Component\User\Dtos\UserProjectsDto;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;

readonly class UserGetProjectsService
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(): ArrayCollection
    {
        $users = $this->userRepository->findBy(['deletedBy' => null]);

        $result = [];

        foreach ($users as $user) {
            $projects = $this->projectRepository->findBy(['executor' => $user, 'deletedBy' => null]);
            $projectsData = [];

            foreach ($projects as $project) {
                $projectsData[] = new UserProjectsDetailDto(
                    $project->getId(),
                    $project->getName(),
                    $project->isActive(),
                    $project->getChargeDay(),
                    $project->getPrice(),
                    $project->getGraphicPostCount(),
                    $project->getVideoPostCount(),
                );
            }

            $result[] = new UserProjectsDto($user->getGivenName(), $user->getFamilyName(), $projectsData);
        }

        return new ArrayCollection($result);
    }
}
