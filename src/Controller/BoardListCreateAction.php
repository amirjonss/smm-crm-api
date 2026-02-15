<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Base\AbstractController;
use App\Entity\BoardList;
use App\Repository\BoardListRepository;
use App\Service\BoardListSetPositionService;
use Doctrine\ORM\EntityManagerInterface;

class BoardListCreateAction extends AbstractController
{
    public function __invoke(
        BoardList $boardList,
        EntityManagerInterface $entityManager,
        BoardListSetPositionService $setPositionService,
        BoardListRepository $boardListRepository,
    ): BoardList {
        $prevList = $boardListRepository->findBoardListByLastPositionInBoard($boardList->getBoard(), $boardList);

        $boardList->setCreatedAt(new \DateTime());
        $boardList->setPosition($setPositionService->calculatePosition($boardList, $boardList->getBoard(), $prevList, null));
        $boardList->setCreatedBy($this->getUser());
        $entityManager->persist($boardList);
        $entityManager->flush();

        return $boardList;
    }
}
