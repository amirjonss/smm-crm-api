<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $smmUser = $this->getReference('user-smm', User::class);
        $smmUser2 = $this->getReference('user-smm-2', User::class);

        $project1 = new Project();
        $project1->setName('Test Project 1');
        $project1->setExecutor($smmUser);
        $project1->setPhone('998 (90) 101 - 11 - 11');
        $project1->setCreatedAt(new \DateTime());
        $project1->setCreatedBy($smmUser);
        $project1->setChargeDay(5);
        $project1->setPrice(1000000);
        $project1->setGraphicPostCount(12);
        $project1->setVideoPostCount(4);
        $this->addReference('project-1', $project1);
        $manager->persist($project1);

        $project2 = new Project();
        $project2->setName('Test Project 2');
        $project2->setExecutor($smmUser2);
        $project2->setPhone('998 (91) 202 - 22 - 22');
        $project2->setCreatedAt(new \DateTime());
        $project2->setCreatedBy($smmUser2);
        $project2->setChargeDay(10);
        $project2->setPrice(1500000);
        $project2->setGraphicPostCount(16);
        $project2->setVideoPostCount(6);
        $this->addReference('project-2', $project2);
        $manager->persist($project2);

        $project3 = new Project();
        $project3->setName('Test Project 3');
        $project3->setExecutor($smmUser2);
        $project3->setPhone('998 (93) 303 - 33 - 33');
        $project3->setCreatedAt(new \DateTime());
        $project3->setCreatedBy($smmUser);
        $project3->setChargeDay(15);
        $project3->setPrice(2000000);
        $project3->setGraphicPostCount(20);
        $project3->setVideoPostCount(8);
        $this->addReference('project-3', $project3);
        $manager->persist($project3);

        $manager->flush();
    }
}
