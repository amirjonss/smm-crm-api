<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\ContentPlanPlatform;

use App\Entity\ContentPlanPlatform;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetApiTest extends BaseApiTestCase
{
    public function testSuccessGetCollectionContentPlanPlatformsAsAdmin(): void
    {
        $response = $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/content_plan_platforms',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'Collection']);
        $this->assertSame(3, $response->toArray(false)['totalItems']);
    }

    public function testSuccessGetContentPlanPlatformByIdAsAdmin(): void
    {
        $platformIri = $this->findIriBy(ContentPlanPlatform::class, ['name' => 'INSTAGRAM']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            $platformIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            '@id' => $platformIri,
            '@type' => 'ContentPlanPlatform',
            'name' => 'INSTAGRAM',
        ]);
    }
}
