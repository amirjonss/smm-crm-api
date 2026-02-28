<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\User;

use App\Entity\User;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetApiTest extends BaseApiTestCase
{
    public function testAdminCanGetUsersCollection(): void
    {
        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/users',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'Collection']);
    }

    public function testSmmCanGetUsersCollection(): void
    {
        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/users',
            [
                'headers' => [
                    'Accept' => 'application/ld+json',
                ],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testOperatorCannotGetUsersCollection(): void
    {
        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/users',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdminCanGetUserById(): void
    {
        $userIri = $this->findIriBy(User::class, ['email' => 'operator@example.com']);
        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            $userIri,
            [
                'headers' => [
                    'Accept' => 'application/ld+json',
                ],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            '@id' => $userIri,
        ]);
    }

    public function testSmmCannotGetUserById(): void
    {
        $userIri = $this->findIriBy(User::class, ['email' => 'operator@example.com']);
        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_GET,
            $userIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
