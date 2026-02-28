<?php

declare(strict_types=1);

namespace App\Tests\ApiTest\CardPattern;

use App\Entity\CardPattern;
use App\Tests\ApiTest\BaseApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateApiTest extends BaseApiTestCase
{
    public function testSuccessUpdateCardPattern(): void
    {
        $cardPatternIri = $this->findIriBy(CardPattern::class, ['name' => 'Test Card Pattern 1']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $cardPatternIri,
            [
                'body' => json_encode([
                    'name' => 'Updated Card Pattern Name',
                    'description' => 'Updated card pattern description',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->createSmmClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $cardPatternIri,
            [
                'body' => json_encode([
                    'name' => 'Updated Card Pattern Name 2',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testFailUpdateCardPattern(): void
    {
        $cardPatternIri = $this->findIriBy(CardPattern::class, ['name' => 'Test Card Pattern 2']);

        $this->createAdminClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $cardPatternIri,
            [
                'body' => json_encode([
                    'deadline' => 'wrong-date',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testFailUpdateCardPatternByRole(): void
    {
        $cardPatternIri = $this->findIriBy(CardPattern::class, ['name' => 'Test Card Pattern 3']);

        $this->createOperatorClientWithCredentials()->request(
            Request::METHOD_PATCH,
            $cardPatternIri,
            [
                'body' => json_encode([
                    'name' => 'Cannot update',
                ]),
                'headers' => ['content-type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
