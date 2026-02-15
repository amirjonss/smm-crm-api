<?php

declare(strict_types=1);

namespace App\Component\BoardList\Dtos;

use App\Entity\Board;
use App\Entity\BoardList;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class BoardListMovePositionDto
{
    public function __construct(
        #[Groups(['board-list:move-position:write'])]
        private BoardList $boardList,
        #[Groups(['board-list:move-position:write'])]
        private ?BoardList $prevBoardList = null,
        #[Groups(['board-list:move-position:write'])]
        private ?BoardList $nextBoardList = null,
        #[Groups(['board-list:move-position:write'])]
        private ?Board $targetBoard = null,
    ) {
    }

    public function getBoardList(): BoardList
    {
        return $this->boardList;
    }

    public function getPrevBoardList(): ?BoardList
    {
        return $this->prevBoardList;
    }

    public function getNextBoardList(): ?BoardList
    {
        return $this->nextBoardList;
    }

    public function getTargetBoard(): ?Board
    {
        return $this->targetBoard;
    }
}
