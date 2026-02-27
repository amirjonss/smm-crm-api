<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\BoardList;

use App\Entity\Board;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateApiTest extends BaseApiTestCase
{
    public function testSuccessCreateBoardList(): void
    {
        $boardIri = $this->findIriBy(Board::class, ['name' => 'Test Board 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/board_lists',
            [
                'body' => json_encode([
                    'board' => $boardIri,
                    'name' => 'board name',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/board_lists',
            [
                'body' => json_encode([
                    'board' => $boardIri,
                    'name' => 'board name 2',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testFailCreateBoardList(): void
    {
        $boardIri = $this->findIriBy(Board::class, ['name' => 'Test Board 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/board_lists',
            [
                'body' => json_encode([
                    'board' => 'invalid_iri',
                    'name' => 'board name',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/board_lists',
            [
                'body' => json_encode([
                    'board' => $boardIri,
                    'name' => '',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testFailCreateBoardListByRole(): void
    {
        $boardIri = $this->findIriBy(Board::class, ['name' => 'Test Board 1']);

        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/board_lists',
            [
                'body' => json_encode([
                    'board' => $boardIri,
                    'name' => 'board name',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
