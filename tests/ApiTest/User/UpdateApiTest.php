<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\User;

use App\Component\User\Enum\Roles;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateApiTest extends BaseApiTestCase
{
    /**
     * The general PATCH endpoint may only change profile fields.
     * roles/email are intentionally NOT writable here (privilege-escalation fix):
     * they live on the dedicated admin-only endpoints, so sending them must be ignored.
     */
    public function testAdminCanUpdateProfileFields(): void
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
            // role stays as created (ROLE_SMM) — not changeable via the general endpoint
            'roles' => [Roles::SMM->value],
        ]);
    }

    /**
     * Regression test for the privilege-escalation fix: a non-admin user is allowed to
     * PATCH their own record, but must NOT be able to grant themselves a new role.
     */
    public function testUserCannotEscalateOwnRoleViaGeneralPatch(): void
    {
        $client = $this->createSmmClientWithCredentials();

        $ownIri = $client->request(
            Request::METHOD_POST,
            '/api/users/about_me',
            ['headers' => ['content-type' => 'application/ld+json']]
        )->toArray()['@id'];

        $client->request(
            Request::METHOD_PATCH,
            $ownIri,
            [
                'body' => json_encode(['roles' => [Roles::ADMIN->value]]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        // Editing self is allowed, but the role must remain unchanged.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['roles' => [Roles::SMM->value]]);
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

    public function testAdminCanChangeRole(): void
    {
        $userIri = $this->createUserAndGetIri();

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $userIri . '/change-role',
            [
                'body' => json_encode(['roles' => [Roles::OPERATOR->value]]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['roles' => [Roles::OPERATOR->value]]);
    }

    public function testNonAdminCannotChangeRole(): void
    {
        $userIri = $this->createUserAndGetIri();

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $userIri . '/change-role',
            [
                'body' => json_encode(['roles' => [Roles::OPERATOR->value]]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdminCanChangeEmail(): void
    {
        $userIri = $this->createUserAndGetIri();
        $newEmail = sprintf('changed-%s@example.com', uniqid('', true));

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $userIri . '/change-email',
            [
                'body' => json_encode(['email' => $newEmail]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['email' => $newEmail]);
    }

    public function testChangeRoleWithInvalidRolesReturnsUnprocessableEntity(): void
    {
        $userIri = $this->createUserAndGetIri();

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $userIri . '/change-role',
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

    public function testChangeEmailWithInvalidEmailReturnsUnprocessableEntity(): void
    {
        $userIri = $this->createUserAndGetIri();

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $userIri . '/change-email',
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
