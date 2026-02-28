<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\CardLog;

use App\Entity\Card;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateApiTest extends BaseApiTestCase
{
    public function testSuccessUpdateOwnCardLog(): void
    {
        $cardLogIri = $this->createCardLogAsAdmin('Card log before update');

        $response = $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $cardLogIri,
            [
                'body' => json_encode([
                    'description' => 'Card log after update',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSame('Card log after update', $response->toArray(false)['description']);
    }

    public function testFailUpdateCardLogByOwnerRule(): void
    {
        $cardLogIri = $this->createCardLogAsAdmin('Only owner can update this');

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $cardLogIri,
            [
                'body' => json_encode([
                    'description' => 'Smm cannot update this',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testFailUpdateCardLog(): void
    {
        $cardLogIri = $this->createCardLogAsAdmin('Initial description');

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $cardLogIri,
            [
                'body' => json_encode([
                    'description' => '',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function createCardLogAsAdmin(string $description): string
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 1']);

        $response = $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/card_logs',
            [
                'body' => json_encode([
                    'card' => $cardIri,
                    'description' => $description,
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        return $response->toArray(false)['@id'];
    }
}
