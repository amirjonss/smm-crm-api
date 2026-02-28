<?php

declare(strict_types=1);

namespace App\Controller;

use App\Component\BoardList\Dtos\BoardListMovePositionDto;
use App\Controller\Base\AbstractController;
use App\Service\BoardListSetPositionService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class BoardListMovePositionAction extends AbstractController
{
    public function __invoke(
        BoardListMovePositionDto $boardListMovePositionDto,
        BoardListSetPositionService $service,
    ): Response {
        $boardList = $boardListMovePositionDto->getBoardList();

        try {
            $service->setPositionBoardList(
                $boardList,
                $boardListMovePositionDto->getPrevBoardList(),
                $boardListMovePositionDto->getNextBoardList(),
                $boardListMovePositionDto->getTargetBoard(),
            );
        } catch (\Throwable $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $this->responseNormalized($boardList);
    }
}
