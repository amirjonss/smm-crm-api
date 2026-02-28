<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\CardPattern;

use App\Entity\CardPattern;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetApiTest extends BaseApiTestCase
{
    public function testSuccessGetCollectionCardPatterns(): void
    {
        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/card_patterns',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'Collection']);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/card_patterns',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testSuccessGetCardPatternById(): void
    {
        $cardPatternIri = $this->findIriBy(CardPattern::class, ['name' => 'Test Card Pattern 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_GET,
            $cardPatternIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(['@type' => 'CardPattern']);
    }

    public function testFailGetCardPatternsByRole(): void
    {
        $cardPatternIri = $this->findIriBy(CardPattern::class, ['name' => 'Test Card Pattern 1']);

        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_GET,
            '/api/card_patterns',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_GET,
            $cardPatternIri,
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
