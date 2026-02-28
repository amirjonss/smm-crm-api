<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\Project;

use App\Entity\Project;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateApiTest extends BaseApiTestCase
{
    public function testAdminCanUpdateProjectViaDefaultEndpoint(): void
    {
        $projectIri = $this->findIriBy(Project::class, ['name' => 'Test Project 1']);

        $response = $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $projectIri,
            [
                'body' => json_encode([
                    'name' => 'Updated Project Name',
                    'phone' => '998 (90) 777 - 77 - 77',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            '@id' => $projectIri,
            'name' => 'Updated Project Name',
            'phone' => '998 (90) 777 - 77 - 77',
        ]);
        $this->assertSame('Updated Project Name', $response->toArray(false)['name']);
    }

    public function testExecutorSmmCanUpdateOwnProjectViaDefaultEndpoint(): void
    {
        $projectIri = $this->findIriBy(Project::class, ['name' => 'Test Project 1']);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $projectIri,
            [
                'body' => json_encode([
                    'name' => 'Updated Project Name By Smm',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            '@id' => $projectIri,
            'name' => 'Updated Project Name By Smm',
        ]);
    }

    public function testUpdateProjectWithInvalidPhoneReturnsUnprocessableEntity(): void
    {
        $projectIri = $this->findIriBy(Project::class, ['name' => 'Test Project 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $projectIri,
            [
                'body' => json_encode([
                    'phone' => 'wrong',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSmmCannotUpdateProjectOfAnotherExecutor(): void
    {
        $projectIri = $this->findIriBy(Project::class, ['name' => 'Test Project 2']);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $projectIri,
            [
                'body' => json_encode([
                    'name' => 'Should fail for non-executor smm',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testSuccessAdminEndpointUpdateProject(): void
    {
        $projectIri = $this->findIriBy(Project::class, ['name' => 'Test Project 2']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $projectIri . '/admin',
            [
                'body' => json_encode([
                    'isActive' => false,
                    'chargeDay' => 20,
                    'price' => 2500000,
                    'graphicPostCount' => 30,
                    'videoPostCount' => 12,
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            '@id' => $projectIri . '/admin',
            '@type' => 'Project',
            'name' => 'Test Project 2',
        ]);
    }

    public function testSmmCannotUseAdminEndpoint(): void
    {
        $projectIri = $this->findIriBy(Project::class, ['name' => 'Test Project 1']);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $projectIri . '/admin',
            [
                'body' => json_encode([
                    'price' => 1000,
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdminEndpointValidatesFields(): void
    {
        $projectIri = $this->findIriBy(Project::class, ['name' => 'Test Project 2']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $projectIri . '/admin',
            [
                'body' => json_encode([
                    'chargeDay' => 0,
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $projectIri . '/admin',
            [
                'body' => json_encode([
                    'price' => -1,
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
