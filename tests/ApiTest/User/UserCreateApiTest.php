<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\User;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\InMemoryUser;

class UserCreateApiTest extends ApiTestCase
{
    protected static ?bool $alwaysBootKernel = true;

    public function testCorrectCreatingUser(): void
    {
        $client = static::createClient();
        $testUser = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($testUser);

        $response = $client->request(
            Request::METHOD_POST,
            '/api/users',
            [
                'headers' => [
                    'Accept' => 'application/ld+json',
                    'Content-Type' => 'application/ld+json',
                ],
                'body' => json_encode([
                    'email' => 'test@example.com',
                    'password' => 'string',
                    'roles' => ['ROLE_ADMIN'],
                    'givenName' => 'John',
                    'familyName' => 'Doe',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(201);
    }
}
