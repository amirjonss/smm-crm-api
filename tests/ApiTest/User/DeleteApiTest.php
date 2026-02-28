<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\User;

use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteApiTest extends BaseApiTestCase
{
    public function testAdminCanDeleteUser(): void
    {
        $adminClient = $this->createAdminClientWithCredentials();
        $email = sprintf('delete-%s@example.com', uniqid('', true));

        $createResponse = $adminClient->request(
            Request::METHOD_POST,
            '/api/users',
            [
                'body' => json_encode([
                    'email' => $email,
                    'password' => 'string123',
                    'roles' => ['ROLE_SMM'],
                    'givenName' => 'Delete',
                    'familyName' => 'Me',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $createdUser = $createResponse->toArray(false);
        $userIri = $createdUser['@id'] ?? null;
        $this->assertNotNull($userIri);

        $adminClient->request(
            Request::METHOD_DELETE,
            $userIri
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $adminClient->request(
            Request::METHOD_GET,
            $userIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testOperatorCannotDeleteUser(): void
    {
        $adminClient = $this->createAdminClientWithCredentials();
        $email = sprintf('forbidden-delete-%s@example.com', uniqid('', true));

        $createResponse = $adminClient->request(
            Request::METHOD_POST,
            '/api/users',
            [
                'body' => json_encode([
                    'email' => $email,
                    'password' => 'string123',
                    'roles' => ['ROLE_SMM'],
                    'givenName' => 'Protected',
                    'familyName' => 'User',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $createdUser = $createResponse->toArray(false);
        $userIri = $createdUser['@id'] ?? null;
        $this->assertNotNull($userIri);

        $operatorClient = $this->createOperatorClientWithCredentials();
        $operatorClient->request(
            Request::METHOD_DELETE,
            $userIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
