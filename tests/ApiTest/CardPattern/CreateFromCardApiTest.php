<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\CardPattern;

use App\Entity\Card;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateFromCardApiTest extends BaseApiTestCase
{
    public function testSuccessCreateCardPatternFromCard(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/card-patterns/from-card',
            [
                'body' => json_encode([
                    'card' => $cardIri,
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/card-patterns/from-card',
            [
                'body' => json_encode([
                    'card' => $cardIri,
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testFailCreateCardPatternFromCard(): void
    {
        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/card-patterns/from-card',
            [
                'body' => json_encode([
                    'card' => 'wrong_iri',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testFailCreateCardPatternFromCardByRole(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 2']);

        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/card-patterns/from-card',
            [
                'body' => json_encode([
                    'card' => $cardIri,
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
