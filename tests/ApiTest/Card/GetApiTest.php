<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\Card;

use App\Entity\Card;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetApiTest extends BaseApiTestCase
{
    public function testSuccessGetCollectionCards(): void
    {
        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/cards',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'Collection']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/cards/archived',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'Collection']);
    }

    public function testSuccessGetCardById(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            $cardIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'Card']);
    }
}
