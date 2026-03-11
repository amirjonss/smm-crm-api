<?php

declare(strict_types=1);

namespace App\Component\Board;

use App\Entity\BoardList;
use App\Entity\Card;
use App\Entity\CardLog;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class MercurePublisher
{
    public function __construct(private HubInterface $hub, private LoggerInterface $logger)
    {
    }

    public function publishCardCreated(Card $card): void
    {
        $this->publish('card.created', $this->buildCardPayload($card), $card->getList());
    }

    public function publishCardUpdated(Card $card): void
    {
        $this->publish('card.updated', $this->buildCardPayload($card), $card->getList());
    }

    public function publishCardMoved(Card $card, int $fromListId): void
    {
        $data = $this->buildCardPayload($card);
        $data['fromListId'] = $fromListId;
        $data['toListId'] = $card->getList()?->getId();
        $this->publish('card.moved', $data, $card->getList());
    }

    public function publishCardArchived(Card $card, bool $isArchived): void
    {
        $this->publish('card.archived', [
            'cardId' => $card->getId(),
            'isArchived' => $isArchived,
        ], $card->getList());
    }

    public function publishListUpdated(BoardList $list): void
    {
        $this->publish('list.updated', $this->buildListPayload($list), $list);
    }

    public function publishListCreated(BoardList $list): void
    {
        $this->publish('list.created', $this->buildListPayload($list), $list);
    }

    public function publishCardLogAdded(CardLog $log): void
    {
        $card = $log->getCard();
        if ($card === null) {
            return;
        }

        $createdBy = $log->getCreatedBy();
        $createdBy = $createdBy instanceof User ? $createdBy : null;
        $this->publish('card.log_added', [
            'cardId' => $card->getId(),
            'log' => [
                'id' => $log->getId(),
                'description' => $log->getDescription(),
                'type' => $log->getType(),
                'createdAt' => $log->getCreatedAt()?->format(\DateTimeInterface::ATOM),
                'createdBy' => $createdBy ? [
                    'id' => $createdBy->getId(),
                    'givenName' => $createdBy->getGivenName(),
                    'familyName' => $createdBy->getFamilyName(),
                ] : null,
            ],
        ], $card->getList());
    }

    private function publish(string $type, array $data, ?BoardList $list): void
    {
        if ($list === null) {
            return;
        }

        $boardId = $list->getBoard()?->getId();
        if ($boardId === null) {
            return;
        }

        try {
            $this->hub->publish(new Update(
                'board/' . $boardId,
                json_encode(['type' => $type, 'data' => $data]),
            ));
        } catch (\Throwable $e) {
            $this->logger->error('[MercurePublisher] publish failed: ' . $e->getMessage(), [
                'type' => $type,
                'boardId' => $boardId,
                'exception' => $e,
            ]);
        }
    }

    private function buildCardPayload(Card $card): array
    {
        $executors = [];
        foreach ($card->getExecutor() as $user) {
            $executors[] = [
                'id' => $user->getId(),
                'givenName' => $user->getGivenName(),
                'familyName' => $user->getFamilyName(),
            ];
        }

        return [
            '@id' => '/api/cards/' . $card->getId(),
            'id' => $card->getId(),
            'name' => $card->getName(),
            'status' => $card->getStatus()->value,
            'isArchived' => $card->isArchived(),
            'deadline' => $card->getDeadline()?->format(\DateTimeInterface::ATOM),
            'description' => $card->getDescription(),
            'color' => $card->getColor(),
            'position' => $card->getPosition(),
            'list' => [
                'id' => $card->getList()?->getId(),
                'name' => $card->getList()?->getName(),
            ],
            'executor' => $executors,
        ];
    }

    private function buildListPayload(BoardList $list): array
    {
        return [
            '@id' => '/api/board_lists/' . $list->getId(),
            'id' => $list->getId(),
            'name' => $list->getName(),
            'color' => $list->getColor(),
            'position' => $list->getPosition(),
            'isArchived' => $list->isArchived(),
            'board' => '/api/boards/' . $list->getBoard()?->getId(),
        ];
    }
}
