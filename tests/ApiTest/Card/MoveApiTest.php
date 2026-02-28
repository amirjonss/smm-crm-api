<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\Card;

use App\Entity\Card;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MoveApiTest extends BaseApiTestCase
{
    public function testSuccessMoveCard(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 1']);
        $prevCardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 2']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/cards/move-position',
            [
                'body' => json_encode([
                    'card' => $cardIri,
                    'prevCard' => $prevCardIri,
                    'nextCard' => null,
                    'targetList' => null,
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testFailMoveCardWithInvalidPayload(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/cards/move-position',
            [
                'body' => json_encode([
                    'card' => $cardIri,
                    'prevCard' => 'wrong_iri',
                    'nextCard' => null,
                    'targetList' => null,
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
