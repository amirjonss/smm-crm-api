<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\User;

use App\Component\User\Enum\Roles;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateApiTest extends BaseApiTestCase
{
    public function testCorrectCreatingUser(): void
    {
        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/users',
            [
                'body' => json_encode([
                    'email' => 'test@example.com',
                    'password' => 'string',
                    'roles' => [Roles::ADMIN->value],
                    'givenName' => 'John',
                    'familyName' => 'Doe',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testIncorrectCreatingUserByRole(): void
    {
        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/users',
            [
                'body' => json_encode([
                    'email' => 'test@example.com',
                    'password' => 'string',
                    'roles' => [Roles::ADMIN->value, Roles::OPERATOR->value],
                    'givenName' => 'John',
                    'familyName' => 'Doe',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testIncorrectCreatingUserWithDuplicateEmail(): void
    {
        $email = sprintf('duplicate-%s@example.com', uniqid('', true));

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/users',
            [
                'body' => json_encode([
                    'email' => $email,
                    'password' => 'string',
                    'roles' => [Roles::SMM->value],
                    'givenName' => 'John',
                    'familyName' => 'Doe',
                ]),
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/users',
            [
                'body' => json_encode([
                    'email' => $email,
                    'password' => 'string',
                    'roles' => [Roles::OPERATOR->value],
                    'givenName' => 'John',
                    'familyName' => 'Doe',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
