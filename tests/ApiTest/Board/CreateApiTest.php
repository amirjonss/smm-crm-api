<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\Board;

use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateApiTest extends BaseApiTestCase
{
    public function testSuccessCreateBoard(): void
    {
        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/boards',
            [
                'body' => json_encode([
                    'name' => 'Test board',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testIncorrectCreateBoard(): void
    {
        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/boards',
            [
                'body' => json_encode([
                    'name' => '',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testIncorrectCreateBoardByRole(): void
    {
        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/boards',
            [
                'body' => json_encode([
                    'name' => 'Test board',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
