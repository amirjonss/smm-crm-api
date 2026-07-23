<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Base\AbstractController;
use App\Entity\Project;
use App\Service\ProjectCreateService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectCreateAction extends AbstractController
{
    public function __invoke(
        Project $project,
        Request $request,
        ProjectCreateService $projectCreateService,
    ): Response {
        return $this->responseNormalized($projectCreateService->create($project), Response::HTTP_CREATED);
    }
}
