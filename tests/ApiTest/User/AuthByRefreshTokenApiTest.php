<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\User;

use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthByRefreshTokenApiTest extends BaseApiTestCase
{
    public function testNullRefreshTokenReturnsValidationError(): void
    {
        static::createSmmClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/users/auth/refreshToken',
            [
                'json' => [
                    'refreshToken' => null,
                ],
                'headers' => [
                    'content-type' => 'application/ld+json',
                ],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
