<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\BoardList;

use App\Entity\Board;
use App\Entity\BoardList;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MoveApiTest extends BaseApiTestCase
{
    public function testSuccessMoveBoardList(): void
    {
        $boardListIri = $this->findIriBy(BoardList::class, ['name' => 'Test Board List 1']);
        $prevBoardListIri = $this->findIriBy(BoardList::class, ['name' => 'Test Board List 2']);
        $targetBoardIri = $this->findIriBy(Board::class, ['name' => 'Test Board 2']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/board_lists/move-position',
            [
                'body' => json_encode([
                    'boardList' => $boardListIri,
                    'prevBoardList' => $prevBoardListIri,
                    'nextBoardList' => null,
                    'targetBoard' => $targetBoardIri,
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testFailMoveBoardListByRole(): void
    {
        $boardListIri = $this->findIriBy(BoardList::class, ['name' => 'Test Board List 1']);
        $prevBoardListIri = $this->findIriBy(BoardList::class, ['name' => 'Test Board List 2']);
        $targetBoardIri = $this->findIriBy(Board::class, ['name' => 'Test Board 3']);

        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/board_lists/move-position',
            [
                'body' => json_encode([
                    'boardList' => $boardListIri,
                    'prevBoardList' => $prevBoardListIri,
                    'nextBoardList' => null,
                    'targetBoard' => $targetBoardIri,
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
