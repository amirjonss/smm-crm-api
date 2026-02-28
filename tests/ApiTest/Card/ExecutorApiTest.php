<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\Card;

use App\Entity\Card;
use App\Entity\User;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExecutorApiTest extends BaseApiTestCase
{
    public function testSuccessAddExecutorToCard(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 1']);
        $executorIri = $this->findIriBy(User::class, ['email' => 'operator@example.com']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            $cardIri . '/executor',
            [
                'body' => json_encode([
                    'executor' => $executorIri,
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testSuccessDeleteExecutorFromCard(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 2']);
        $executorIri = $this->findIriBy(User::class, ['email' => 'operator@example.com']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            $cardIri . '/executor',
            [
                'body' => json_encode([
                    'executor' => $executorIri,
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            $cardIri . '/executor-delete',
            [
                'body' => json_encode([
                    'executor' => $executorIri,
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testFailExecutorActionsByRole(): void
    {
        $cardIri = $this->findIriBy(Card::class, ['name' => 'Test Card 3']);
        $executorIri = $this->findIriBy(User::class, ['email' => 'operator@example.com']);

        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_POST,
            $cardIri . '/executor',
            [
                'body' => json_encode([
                    'executor' => $executorIri,
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_POST,
            $cardIri . '/executor-delete',
            [
                'body' => json_encode([
                    'executor' => $executorIri,
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
