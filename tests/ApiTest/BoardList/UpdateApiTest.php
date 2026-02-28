<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\BoardList;

use App\Entity\BoardList;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateApiTest extends BaseApiTestCase
{
    public function testSuccessUpdateBoardList(): void
    {
        $boardListIri = $this->findIriBy(BoardList::class, ['name' => 'Test Board List 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $boardListIri,
            [
                'body' => json_encode([
                    'name' => 'Updated Board List Name',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $boardListIri,
            [
                'body' => json_encode([
                    'name' => 'Updated Board List Name 2',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testFailUpdateBoardList(): void
    {
        $boardListIri = $this->findIriBy(BoardList::class, ['name' => 'Test Board List 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $boardListIri,
            [
                'body' => json_encode([
                    'name' => '',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $boardListIri,
            [
                'body' => json_encode([
                    'board' => 'wrongIri',
                    'name' => 'board list name',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testFailUpdateBoardListByRole(): void
    {
        $boardListIri = $this->findIriBy(BoardList::class, ['name' => 'Test Board List 1']);
        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $boardListIri,
            [
                'body' => json_encode([
                    'name' => 'Updated Board List Name',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
