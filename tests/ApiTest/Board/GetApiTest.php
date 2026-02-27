<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\Board;

use App\Entity\Board;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetApiTest extends BaseApiTestCase
{
    public function testSuccessGetCollectionBoards(): void
    {
        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/boards'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'Collection']);
    }

    public function testSuccessGetBoardById(): void
    {
        $boardIri = $this->findIriBy(Board::class, ['name' => 'Test Board 1']);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_GET,
            $boardIri
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'Board']);
    }

}
