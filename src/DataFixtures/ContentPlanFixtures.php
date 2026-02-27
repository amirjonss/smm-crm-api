<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ContentPlan;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ContentPlanFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ProjectFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $adminUser = $this->getReference('user-admin', User::class);
        $project1 = $this->getReference('project-1', Project::class);
        $project2 = $this->getReference('project-2', Project::class);
        $project3 = $this->getReference('project-3', Project::class);

        $contentPlan1 = new ContentPlan();
        $contentPlan1->setPost('How to choose a CRM for small business');
        $contentPlan1->setFormat('Reels');
        $contentPlan1->setDate(new \DateTime('2026-03-01'));
        $contentPlan1->setIdea('Short comparison of key CRM features with CTA to request consultation.');
        $contentPlan1->setProject($project1);
        $contentPlan1->setPosition(0);
        $contentPlan1->setCreatedAt(new \DateTime());
        $contentPlan1->setCreatedBy($adminUser);
        $this->addReference('content-plan-1', $contentPlan1);
        $manager->persist($contentPlan1);

        $contentPlan2 = new ContentPlan();
        $contentPlan2->setPost('5 content planning mistakes agencies make');
        $contentPlan2->setFormat('Carousel');
        $contentPlan2->setDate(new \DateTime('2026-03-03'));
        $contentPlan2->setIdea('Educational carousel with one mistake per slide and practical fix.');
        $contentPlan2->setProject($project2);
        $contentPlan2->setPosition(1);
        $contentPlan2->setCreatedAt(new \DateTime());
        $contentPlan2->setCreatedBy($adminUser);
        $this->addReference('content-plan-2', $contentPlan2);
        $manager->persist($contentPlan2);

        $contentPlan3 = new ContentPlan();
        $contentPlan3->setPost('Client success case: +35% leads in 60 days');
        $contentPlan3->setFormat('Post');
        $contentPlan3->setDate(new \DateTime('2026-03-05'));
        $contentPlan3->setIdea('Before/after metrics with process highlights and proof screenshots.');
        $contentPlan3->setProject($project3);
        $contentPlan3->setPosition(2);
        $contentPlan3->setCreatedAt(new \DateTime());
        $contentPlan3->setCreatedBy($adminUser);
        $this->addReference('content-plan-3', $contentPlan3);
        $manager->persist($contentPlan3);

        $manager->flush();
    }
}
