<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\CardLog;

use App\Entity\Card;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateApiTest extends BaseApiTestCase
{
    public function testSuccessCreateCardLog(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 1']);

        $response = $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/card_logs',
            [
                'body' => json_encode([
                    'card' => $cardIri,
                    'description' => 'Created card log by operator',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $data = $response->toArray(false);
        $this->assertSame('Created card log by operator', $data['description']);
        $this->assertSame('COMMENT', $data['type']);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/card_logs',
            [
                'body' => json_encode([
                    'card' => $cardIri,
                    'description' => 'Created card log by smm',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testFailCreateCardLog(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 2']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/card_logs',
            [
                'body' => json_encode([
                    'card' => 'invalid_iri',
                    'description' => 'Card log description',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/card_logs',
            [
                'body' => json_encode([
                    'card' => $cardIri,
                    'description' => '',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
