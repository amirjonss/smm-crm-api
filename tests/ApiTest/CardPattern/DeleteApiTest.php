<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\CardPattern;

use App\Entity\CardPattern;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteApiTest extends BaseApiTestCase
{
    public function testSuccessDeleteCardPattern(): void
    {
        $cardPatternIri = $this->findIriBy(CardPattern::class, ['name' => 'Test Card Pattern 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $cardPatternIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testFailDeleteCardPatternByRole(): void
    {
        $cardPatternIri = $this->findIriBy(CardPattern::class, ['name' => 'Test Card Pattern 2']);

        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_DELETE,
            $cardPatternIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
