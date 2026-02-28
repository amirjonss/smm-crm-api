<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\BoardList;

use App\Entity\BoardList;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteApiTest extends BaseApiTestCase
{
    public function testSuccessDeleteBoardList(): void
    {
        $boardListIri = $this->findIriBy(BoardList::class, ['name' => 'Test Board List 1']);
        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $boardListIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testFailDeleteBoardListByRole(): void
    {
        $boardListIri = $this->findIriBy(BoardList::class, ['name' => 'Test Board List 1']);
        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $boardListIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
