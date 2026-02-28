<?php

declare(strict_types=1);

namespace App\Tests\ApiTest;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;

class BaseApiTestCase extends ApiTestCase
{
    private ?string $refreshToken = null;
    private ?string $token = null;

    /**
     * @return string|null
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    protected function createAdminClientWithCredentials(): Client
    {
        $this->getToken();

        return static::createClient([], [
            'headers' => [
                'authorization' => 'Bearer ' . $this->token,
                'content-type' => 'application/ld+json',
            ],
        ]);
    }

    /** Use other credentials if needed. */
    protected function getToken($body = []): string
    {
        $response = static::createClient()->request('POST', '/api/users/auth', [
            'json' => $body ?: [
                'email' => 'admin@example.com',
                'password' => 'passwd',
            ],
            'headers' => ['content-type' => 'application/ld+json'],
        ]);

        self::assertResponseIsSuccessful();
        $data = $response->toArray();
        $this->token = $data['accessToken'];
        $this->refreshToken = $data['refreshToken'];

        return $data['accessToken'];
    }

    protected function createSmmClientWithCredentials(): Client
    {
        $this->getToken([
            'email' => 'smm@example.com',
            'password' => 'passwd',
        ]);

        return static::createClient([], ['headers' => ['authorization' => 'Bearer ' . $this->token, 'content-type' => 'application/ld+json']]);
    }

    protected function createOperatorClientWithCredentials(): Client
    {
        $this->getToken([
            'email' => 'operator@example.com',
            'password' => 'passwd',
        ]);

        return static::createClient([],
            ['headers' => ['authorization' => 'Bearer ' . $this->token, 'content-type' => 'application/ld+json']]);
    }
}
