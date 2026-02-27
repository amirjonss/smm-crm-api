<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\User;

use App\Component\User\Enum\Roles;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateApiTest extends BaseApiTestCase
{
    public function testAdminCanUpdateUser(): void
    {
        $userIri = $this->createUserAndGetIri();

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $userIri,
            [
                'body' => json_encode([
                    'email' => sprintf('updated-%s@example.com', uniqid('', true)),
                    'roles' => [Roles::OPERATOR->value],
                    'givenName' => 'Updated',
                    'familyName' => 'User',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            '@id' => $userIri,
            'givenName' => 'Updated',
            'familyName' => 'User',
            'roles' => ['ROLE_OPERATOR'],
        ]);
    }

    public function testSmmCannotUpdateAnotherUser(): void
    {
        $userIri = $this->createUserAndGetIri();

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $userIri,
            [
                'headers' => [
                    'Accept' => 'application/ld+json',
                    'Content-Type' => 'application/merge-patch+json',
                ],
                'body' => json_encode([
                    'givenName' => 'Nope',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testOperatorCannotUpdateUser(): void
    {
        $userIri = $this->createUserAndGetIri();

        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $userIri,
            [
                'headers' => [
                    'Accept' => 'application/ld+json',
                    'Content-Type' => 'application/merge-patch+json',
                ],
                'body' => json_encode([
                    'givenName' => 'Nope',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testUpdateUserWithInvalidRolesReturnsUnprocessableEntity(): void
    {
        $userIri = $this->createUserAndGetIri();

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $userIri,
            [
                'headers' => [
                    'Accept' => 'application/ld+json',
                    'Content-Type' => 'application/merge-patch+json',
                ],
                'body' => json_encode([
                    'roles' => [Roles::OPERATOR->value, Roles::SMM->value],
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdateUserWithInvalidEmailReturnsUnprocessableEntity(): void
    {
        $userIri = $this->createUserAndGetIri();

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $userIri,
            [
                'headers' => [
                    'Accept' => 'application/ld+json',
                    'Content-Type' => 'application/merge-patch+json',
                ],
                'body' => json_encode([
                    'email' => 'invalid-email-format',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function createUserAndGetIri(): string
    {
        $email = sprintf('update-%s@example.com', uniqid('', true));

        $response = $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/users',
            [
                'body' => json_encode([
                    'email' => $email,
                    'password' => 'string123',
                    'roles' => ['ROLE_SMM'],
                    'givenName' => 'Before',
                    'familyName' => 'Update',
                ]),
                'headers' => ['content-type' => 'application/ld+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $data = $response->toArray(false);

        return (string) ($data['@id'] ?? '');
    }
}
