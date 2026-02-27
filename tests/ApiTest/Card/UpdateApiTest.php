<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\Card;

use App\Entity\Card;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateApiTest extends BaseApiTestCase
{
    public function testSuccessUpdateCard(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $cardIri,
            [
                'body' => json_encode([
                    'name' => 'Updated Card Name',
                    'description' => 'Updated card description',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testFailUpdateCard(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 2']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $cardIri,
            [
                'body' => json_encode([
                    'name' => '',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testAdminCanSetCardStatusToDone(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 1']);

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
    }

    public function testSmmCanSetCardStatusToDone(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 2']);

        $this->createSmmClientWithCredentials()->request(
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
    }

    public function testOperatorCannotSetCardStatusToDone(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 1']);

        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $cardIri,
            [
                'body' => json_encode([
                    'status' => 'done',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
