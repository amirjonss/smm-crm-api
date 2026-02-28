<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\Project;

use App\Entity\Project;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetApiTest extends BaseApiTestCase
{
    public function testSuccessGetCollectionProjects(): void
    {
        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/projects',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'Collection']);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/projects',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testSuccessGetProjectById(): void
    {
        $projectIri = $this->findIriBy(Project::class, ['name' => 'Test Project 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            $projectIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'Project']);
    }
}
