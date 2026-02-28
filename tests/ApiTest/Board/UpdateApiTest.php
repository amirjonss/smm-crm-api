<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\Board;

use App\Entity\Board;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateApiTest extends BaseApiTestCase
{
    public function testSuccessUpdateBoard(): void
    {
        $boardIri = $this->findIriBy(Board::class, ['name' => 'Test Board 1']);
        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $boardIri,
            [
                'body' => json_encode([
                    'name' => 'Updated Board Name',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $boardIri,
            [
                'body' => json_encode([
                    'name' => 'Updated Board Name',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testFailUpdateBoard(): void
    {
        $boardIri = $this->findIriBy(Board::class, ['name' => 'Test Board 1']);
        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $boardIri,
            [
                'body' => json_encode([
                    'name' => '',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testFailUpdateBoardByRole(): void
    {
        $boardIri = $this->findIriBy(Board::class, ['name' => 'Test Board 1']);
        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $boardIri,
            [
                'body' => json_encode([
                    'name' => 'Updated Board Name',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
