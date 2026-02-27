<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\Project;

use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateApiTest extends BaseApiTestCase
{
    public function testSuccessCreateProject(): void
    {
        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/projects',
            [
                'body' => json_encode([
                    'name' => 'Created Project By Smm',
                    'phone' => '998 (91) 555 - 55 - 55',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testFailCreateProject(): void
    {
        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/projects',
            [
                'body' => json_encode([
                    'name' => '',
                    'phone' => '998 (90) 444 - 44 - 44',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/projects',
            [
                'body' => json_encode([
                    'name' => 'Project with bad phone',
                    'phone' => 'invalid_phone',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testFailCreateProjectByOperatorRole(): void
    {
        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/projects',
            [
                'body' => json_encode([
                    'name' => 'Created Project By Operator',
                    'phone' => '998 (93) 666 - 66 - 66',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
