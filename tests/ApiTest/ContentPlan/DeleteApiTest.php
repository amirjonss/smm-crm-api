<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\ContentPlan;

use App\Entity\ContentPlan;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteApiTest extends BaseApiTestCase
{
    public function testSuccessDeleteContentPlanByProjectExecutor(): void
    {
        $contentPlanIri = $this->findIriBy(ContentPlan::class, ['post' => 'How to choose a CRM for small business']);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $contentPlanIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testExecutorSmmCanNotDeleteAnotherExecutorContentPlan(): void
    {
        $contentPlanIri = $this->findIriBy(ContentPlan::class, ['post' => '5 content planning mistakes agencies make']);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $contentPlanIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
