<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\Project;

use App\Entity\Project;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteApiTest extends BaseApiTestCase
{
    public function testSuccessDeleteProject(): void
    {
        $projectIri = $this->findIriBy(Project::class, ['name' => 'Test Project 1']);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $projectIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testFailDeleteProjectBySecurity(): void
    {
        $smmProjectIri = $this->findIriBy(Project::class, ['name' => 'Test Project 2']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $smmProjectIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
