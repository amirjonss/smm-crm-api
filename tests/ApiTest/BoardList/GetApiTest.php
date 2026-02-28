<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\BoardList;

use App\Entity\BoardList;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetApiTest extends BaseApiTestCase
{
    public function testSuccessGetCollectionBoardLists(): void
    {
        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/board_lists',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'Collection']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/board_lists/archived',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'Collection']);
    }

    public function testSuccessGetBoardListById(): void
    {
        $boardListIri = $this->findIriBy(BoardList::class, ['name' => 'Test Board List 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            $boardListIri
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'BoardList']);
    }
}
