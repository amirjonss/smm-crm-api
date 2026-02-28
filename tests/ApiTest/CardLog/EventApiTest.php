<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\CardLog;

use App\Entity\Card;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventApiTest extends BaseApiTestCase
{
    public function testRenameCardCreatesCardLog(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $cardIri,
            [
                'body' => json_encode([
                    'name' => 'Renamed Test Card 1',
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
        $data = $response->toArray(false);
        $this->assertSame(1, $data['totalItems']);
        $this->assertSame(
            'admin adminov переименовал(а) карточку с «Test Card 1» на «Renamed Test Card 1»',
            $data['member'][0]['description']
        );
    }

    public function testChangeStatusCreatesCardLog(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 2']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $cardIri,
            [
                'body' => json_encode([
                    'status' => 'done',
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
        $data = $response->toArray(false);
        $this->assertSame(1, $data['totalItems']);
        $this->assertSame(
            'admin adminov изменил(а) статус на «Выполнено»',
            $data['member'][0]['description']
        );
    }

    public function testUpdateCardDescriptionDoesNotCreateCardLog(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 3']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $cardIri,
            [
                'body' => json_encode([
                    'description' => 'Updated description only',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/card_logs',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSame(0, $response->toArray(false)['totalItems']);
    }
}
