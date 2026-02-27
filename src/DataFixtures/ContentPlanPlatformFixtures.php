<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ContentPlan;
use App\Entity\ContentPlanPlatform;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ContentPlanPlatformFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            ContentPlanFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $contentPlan1 = $this->getReference('content-plan-1', ContentPlan::class);
        $contentPlan2 = $this->getReference('content-plan-2', ContentPlan::class);
        $contentPlan3 = $this->getReference('content-plan-3', ContentPlan::class);

        $platform1 = new ContentPlanPlatform();
        $platform1->setName('INSTAGRAM');
        $platform1->setStatus('NOT_PUBLISHED');
        $platform1->addContentPlan($contentPlan1);
        $this->addReference('content-plan-platform-1', $platform1);
        $manager->persist($platform1);

        $platform2 = new ContentPlanPlatform();
        $platform2->setName('TELEGRAM');
        $platform2->setStatus('PUBLISHED');
        $platform2->addContentPlan($contentPlan2);
        $this->addReference('content-plan-platform-2', $platform2);
        $manager->persist($platform2);

        $platform3 = new ContentPlanPlatform();
        $platform3->setName('YOUTUBE');
        $platform3->setStatus('RESCHEDULED');
        $platform3->addContentPlan($contentPlan3);
        $this->addReference('content-plan-platform-3', $platform3);
        $manager->persist($platform3);

        $manager->flush();
    }
}
