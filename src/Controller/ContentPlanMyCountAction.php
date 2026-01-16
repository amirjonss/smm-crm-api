<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\ContentPlanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ContentPlanMyCountAction extends AbstractController
{
    public function __construct(private ContentPlanRepository $contentPlanRepository)
    {
    }

    public function __invoke(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new JsonResponse(['error' => 'User not found'], 401);
        }

        $startDate = new \DateTime('first day of this month 00:00:00');
        $endDate = new \DateTime('last day of this month 23:59:59');

        $count = $this->contentPlanRepository->countPublishedContentPlansForUser(
            $user,
            $startDate,
            $endDate
        );

        return new JsonResponse(['count' => $count]);
    }
}
