<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Base\AbstractController;
use App\Service\UserGetProjectsService;
use Symfony\Component\HttpFoundation\Response;

class UserProjectsAction extends AbstractController
{
    public function __invoke(UserGetProjectsService $getUserProjectsService): Response
    {
        return $this->responseNormalized(($getUserProjectsService)());
    }
}
