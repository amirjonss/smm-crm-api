<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\ContentPlan;

use App\Entity\ContentPlan;
use App\Entity\Project;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateApiTest extends BaseApiTestCase
{
    public function testSuccessExecutorCanUpdateOwnContentPlan(): void
    {
        $contentPlanIri = $this->findIriBy(ContentPlan::class, ['post' => 'How to choose a CRM for small business']);
        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $contentPlanIri,
            [
                'body' => json_encode([
                    'post' => 'Plan after patch',
                    'idea' => 'Updated idea',
                    'position' => 7,
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            '@id' => $contentPlanIri,
            'post' => 'Plan after patch',
            'idea' => 'Updated idea',
            'position' => 7,
        ]);
    }

    public function testFailUpdateContentPlanWithInvalidFormat(): void
    {
        $contentPlanIri = $this->findIriBy(ContentPlan::class, ['post' => 'How to choose a CRM for small business']);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $contentPlanIri,
            [
                'body' => json_encode([
                    'format' => 'Video',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
