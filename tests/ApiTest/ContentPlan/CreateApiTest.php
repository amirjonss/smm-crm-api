<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\ContentPlan;

use App\Entity\Project;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateApiTest extends BaseApiTestCase
{
    public function testSuccessCreateContentPlanBySmm(): void
    {
        $projectIri = $this->findIriBy(Project::class, ['name' => 'Test Project 1']);

        $response = $this->createSmmClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/content_plans',
            [
                'body' => json_encode([
                    'project' => $projectIri,
                    'post' => 'Created content plan by SMM',
                    'format' => 'Story',
                    'date' => '2026-03-07',
                    'idea' => 'Short story set with CTA',
                    'position' => 3,
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJsonContains([
            '@type' => 'ContentPlan',
            'project' => [
                '@id' => $projectIri,
            ],
            'post' => 'Created content plan by SMM',
            'format' => 'Story',
        ]);
        $this->assertSame('Created content plan by SMM', $response->toArray(false)['post']);
    }

    public function testFailCreateContentPlanByAdminRole(): void
    {
        $projectIri = $this->findIriBy(Project::class, ['name' => 'Test Project 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/content_plans',
            [
                'body' => json_encode([
                    'project' => $projectIri,
                    'post' => 'Created content plan by admin',
                    'format' => 'Post',
                    'date' => '2026-03-08',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testFailCreateContentPlanWithInvalidFormat(): void
    {
        $projectIri = $this->findIriBy(Project::class, ['name' => 'Test Project 1']);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/content_plans',
            [
                'body' => json_encode([
                    'project' => $projectIri,
                    'post' => 'Invalid format content plan',
                    'format' => 'Video',
                    'date' => '2026-03-09',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testFailCreateContentPlanWithInvalidProjectIri(): void
    {
        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_POST,
            '/api/content_plans',
            [
                'body' => json_encode([
                    'project' => 'invalid_iri',
                    'post' => 'Content plan with invalid project',
                    'format' => 'Post',
                    'date' => '2026-03-10',
                ]),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
