<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\Card;

use App\Entity\BoardList;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateApiTest extends BaseApiTestCase
{
    public function testSuccessCreateCard(): void
    {
        $boardListIri = $this->findIriBy(BoardList::class, ['name' => 'Test Board List 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/cards',
            [
                'body' => json_encode([
                    'list' => $boardListIri,
                    'name' => 'Created Card By Admin',
                    'description' => 'Card description',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/cards',
            [
                'body' => json_encode([
                    'list' => $boardListIri,
                    'name' => 'Created Card By Smm',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testFailCreateCard(): void
    {
        $boardListIri = $this->findIriBy(BoardList::class, ['name' => 'Test Board List 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/cards',
            [
                'body' => json_encode([
                    'list' => 'invalid_iri',
                    'name' => 'Card name',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/cards',
            [
                'body' => json_encode([
                    'list' => $boardListIri,
                    'name' => '',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testFailCreateCardByRole(): void
    {
        $boardListIri = $this->findIriBy(BoardList::class, ['name' => 'Test Board List 1']);

        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/cards',
            [
                'body' => json_encode([
                    'list' => $boardListIri,
                    'name' => 'Created Card By Operator',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
