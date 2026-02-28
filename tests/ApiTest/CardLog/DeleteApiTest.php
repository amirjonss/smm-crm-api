<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\CardLog;

use App\Entity\Card;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteApiTest extends BaseApiTestCase
{
    public function testSuccessDeleteOwnCommentCardLog(): void
    {
        $cardLogIri = $this->createCardLogAsOperator('Comment that will be deleted');

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $cardLogIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testFailDeleteCardLogByOwnerRule(): void
    {
        $cardLogIri = $this->createCardLogAsOperator('Comment that smm cannot delete');

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $cardLogIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testFailDeleteSystemLog(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 3']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $cardIri,
            [
                'body' => json_encode([
                    'name' => 'Card renamed to create LOG type',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/card_logs?order[id]=desc',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $cardLogIri = $response->toArray(false)['member'][0]['@id'];

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $cardLogIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function createCardLogAsOperator(string $description): string
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 2']);

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
