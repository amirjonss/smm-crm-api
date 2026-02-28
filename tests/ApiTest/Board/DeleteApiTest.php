<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\Board;

use App\Entity\Board;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteApiTest extends BaseApiTestCase
{
    public function testSuccessDeleteBoard(): void
    {
        $boardIri = $this->findIriBy(Board::class, ['name' => 'Test Board 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $boardIri
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testFailDeleteByRole(): void
    {
        $boardIri = $this->findIriBy(Board::class, ['name' => 'Test Board 1']);
        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $boardIri
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
