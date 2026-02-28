<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\ContentPlan;

use App\Entity\ContentPlan;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetApiTest extends BaseApiTestCase
{
    public function testSuccessGetCollectionContentPlansAsAdmin(): void
    {
        $response = $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/content_plans',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'Collection']);
        $this->assertSame(3, $response->toArray(false)['totalItems']);
    }

    public function testSmmGetsOnlyOwnCreatedContentPlansCollection(): void
    {
        $response = $this->createSmmClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/content_plans',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'Collection']);
        $this->assertSame(1, $response->toArray(false)['totalItems']);
    }

    public function testSuccessGetContentPlanByIdAsAdmin(): void
    {
        $contentPlanIri = $this->findIriBy(ContentPlan::class, ['post' => 'How to choose a CRM for small business']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            $contentPlanIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'ContentPlan']);
    }

    public function testSmmCannotGetForeignContentPlanById(): void
    {
        $contentPlanIri = $this->findIriBy(ContentPlan::class, ['post' => 'Client success case: +35% leads in 60 days']);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_GET,
            $contentPlanIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
