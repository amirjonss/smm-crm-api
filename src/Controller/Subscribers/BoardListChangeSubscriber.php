<?php

declare(strict_types=1);

namespace App\Controller\Subscribers;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Component\Board\MercurePublisher;
use App\Entity\BoardList;
use App\Service\BoardListSetPositionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class BoardListChangeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BoardListSetPositionService $boardListSetPositionService,
        private EntityManagerInterface $entityManager,
        private MercurePublisher $mercurePublisher,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onWrite', EventPriorities::POST_WRITE],
        ];
    }

    public function onWrite(ViewEvent $event): void
    {
        $request = $event->getRequest();
        $boardList = $event->getControllerResult();

        if (!$boardList instanceof BoardList) {
            return;
        }

        if ($request->getMethod() === Request::METHOD_POST) {
            $this->mercurePublisher->publishListCreated($boardList);

            return;
        }

        if ($request->getMethod() !== Request::METHOD_PATCH) {
            return;
        }

        $previousBoardList = $request->attributes->get('previous_data');

        if (!$previousBoardList instanceof BoardList) {
            return;
        }

        $this->archiveAction($previousBoardList, $boardList);
        $this->unarchiveAction($previousBoardList, $boardList);
        $this->mercurePublisher->publishListUpdated($boardList);
    }

    private function archiveAction(BoardList $oldBoardList, BoardList $newBoardList): void
    {
        if ($oldBoardList->isArchived() === false && $newBoardList->isArchived() === true) {
            $newBoardList->setPosition(null);
            $this->entityManager->flush();
        }
    }

    private function unarchiveAction(BoardList $oldBoardList, BoardList $newBoardList): void
    {
        if ($oldBoardList->isArchived() === true && $newBoardList->isArchived() === false) {
            $position = $this->boardListSetPositionService->calculatePosition(
                $newBoardList,
                $newBoardList->getBoard(),
                null,
                null,
            );
            $newBoardList->setPosition($position);
            $this->entityManager->flush();
        }
    }
}
