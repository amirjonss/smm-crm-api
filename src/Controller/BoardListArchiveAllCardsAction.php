<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Base\AbstractController;
use App\Entity\BoardList;
use App\Service\BoardListArchiveAllCardsService;
use Symfony\Component\HttpFoundation\Response;

class BoardListArchiveAllCardsAction extends AbstractController
{
    public function __invoke(
        BoardList $data,
        BoardListArchiveAllCardsService $service,
    ): Response {
        $service->archiveAllCardsInBoardList($data);

        return $this->responseEmpty();
    }
}
