<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\Card;

use App\Entity\Card;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteApiTest extends BaseApiTestCase
{
    public function testSuccessDeleteCard(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $cardIri
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testFailDeleteCardByRole(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 2']);

        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $cardIri
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
