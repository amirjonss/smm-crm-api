<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ContentPlanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ContentPlanCountAction extends AbstractController
{
    public function __construct(private ContentPlanRepository $contentPlanRepository)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $startDate = new \DateTime('first day of this month 00:00:00');
        $endDate = new \DateTime('last day of this month 23:59:59');

        $count = $this->contentPlanRepository->countPublishedContentPlans(
            $startDate,
            $endDate
        );

        return new JsonResponse(['count' => $count]);
    }
}
